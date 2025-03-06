<?php

use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Laravel\Cashier\Subscription;
use function Livewire\Volt\{with, mount};

new class extends Component {
    public $user;
    public $currentSubscription;
    public $currentPlan;

    protected $listeners = ['refresh-subscription' => 'mount'];

    public function mount()
    {
        $this->user = Auth::user();
        $this->currentSubscription = $this->user->subscription();

        $this->currentPlan = Plan::where('price_id', optional($this->currentSubscription)->stripe_price)->first();
    }

    public function cancelSubscription()
    {
        $this->user->subscription()->cancel();
        $this->dispatch('refresh-subscription');
        $this->dispatch('notify',
            variant: 'success',
            message: __('Your subscription has been cancelled successfully.')
        );
    }
}; ?>
<section class="w-full mx-auto">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Your Current Subscription Plan') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Details of your current subscription plan. Manage your subscription.') }}
        </p>
    </header>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            @if($currentSubscription)
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Subscription Status') }}</p>
                        <p class="text-md text-gray-900">
                            @if($currentSubscription->ends_at)
                                <span class="text-red-600">{{ __('Canceled') }}</span>
                            @else
                                <span class="text-green-600">{{ __('Active') }}</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Billing Cycle') }}</p>
                        <p class="text-md text-gray-900">
                            {{ ucfirst($currentPlan->interval) }}ly
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('Next Billing Date') }}</p>
                        <p class="text-md text-gray-900">
                            {{ \Carbon\Carbon::createFromTimestamp($currentSubscription->asStripeSubscription()->current_period_end)->format('M d, Y h:i A') }}
                        </p>
                    </div>

                    @if($currentSubscription->ends_at)
                        <div class="bg-red-100 p-4 rounded-md">
                            <p class="text-md text-red-800">
                                {{ __('Your subscription has been canceled and will end on :date. You will lose access to the app after this date. To continue using the app, please subscribe to a new plan.', ['date' => $currentSubscription->ends_at->format('M d, Y h:i A')]) }}
                            </p>
                        </div>
                    @endif

                    <div class="mt-8 space-y-2">
                        <flux:button variant="danger" wire:confirm="Are you sure you want to cancel your subscription?"
                                     wire:click="cancelSubscription" class="w-full">
                            {{ __('Cancel Subscription') }}
                        </flux:button>
                        <p class="text-sm text-gray-600 text-center mt-4">
                            {{__('If you cancel your subscription, you can still use your plan until the end of the billing period.')}}
                        </p>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-600">
                    {{ __('You are not currently subscribed to any plan.') }}
                </p>
            @endif
        </div>

        <div class="flex justify-center items-center">
            @if($currentSubscription)
                <div class="w-sm max-w-sm">
                    <x-single-plan :plan="$currentPlan" :show-button="false" :is-active="true"/>
                </div>
            @endif
        </div>
    </div>
</section>
