<?php

use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Laravel\Cashier\Subscription;
use function Livewire\Volt\{with, mount};

new class extends Component {
    public $selectedPlanId;
    public $selectedPlan;

    public function mount()
    {
        //
    }

    public function updatedSelectedPlanId($id)
    {
        if ($id){
            $this->selectedPlan = Plan::find($id);
        }else{
            $this->selectedPlan = null;
        }
    }

    public function changePlan()
    {
        $validated = $this->validate([
            'selectedPlanId' => ['required', Rule::exists(Plan::class, 'id')],
        ]);

        $user = Auth::user();
        $plan = Plan::find($validated['selectedPlanId']);

        if ($user->subscribed($plan->price_id)) {
            $this->dispatch('notify',
                variant: 'warning',
                message: __('You are already subscribed to this plan.')
            );

            return;
        }

        $user->subscription()->swap($plan->price_id);

        $this->reset();

        $this->dispatch('refresh-subscription');

        $this->dispatch('notify',
            variant: 'success',
            message: __('Your plan has been changed successfully.')
        );
    }

    public function with()
    {
        return [
            'plans' => Plan::where('status', 1)->get(),
        ];
    }
}; ?>
<section class="w-full mx-auto" xmlns:flex="http://www.w3.org/1999/html">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Upgrade or Change Your Plan') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Choose a plan that best fits your needs, or cancel at any time.') }}
        </p>
    </header>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h4 class="text-md font-medium text-gray-900 mb-4">
                {{ __('Change Plan') }}
            </h4>

            <form wire:submit.prevent="changePlan">
                <div class="space-y-4">
                    <div>
                        <label for="plan" class="block text-sm font-medium text-gray-700">
                            {{ __('Plan') }}
                        </label>
                        <select id="plan" wire:model.live="selectedPlanId"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-md"
                                required
                        >
                            <option value="">{{ __('Select a Plan') }}</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                        @error('plan')
                        <p class="mt-2 text-sm text-red-600" id="plan-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <flux:button type="submit" variant="primary" class="w-full">
                            {{ __('Change Plan') }}
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>

        <div class="flex justify-center items-center">
            @if($selectedPlan)
                <div class="w-sm max-w-sm">
                    <x-single-plan :plan="$selectedPlan" :show-button="false"/>
                </div>
            @else
                <p class="text-sm text-gray-600">
                    {{ __('Select a plan to view details.') }}
                </p>
            @endif
        </div>
    </div>
</section>
