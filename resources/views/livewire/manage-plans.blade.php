<?php

use App\Models\Feature;
use App\Models\Plan;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

new #[Layout('layouts.app')] class extends Component {

    use withPagination;

    public $name;
    public $description;
    public $amount;
    public $interval = 'month';
    public $planFeatures = [];
    public $feature;
    public $value;

    public function __construct()
    {
        parent::__construct();
        Stripe::setApiKey(config('cashier.secret'));
    }

    public function addFeature()
    {
        $this->validate([
            'feature' => ['required', Rule::exists('features', 'id')],
            'value' => ['sometimes', 'nullable', 'string'],
        ]);

        // Add the feature to the plan features array
        $this->planFeatures[] = [
            'id' => $this->feature,
            'name' => Feature::find($this->feature)->name,
            'value' => $this->value,
        ];

        // Reset the feature and value inputs
        $this->reset(['feature', 'value']);
    }

    public function removeFeature($index)
    {
        unset($this->planFeatures[$index]);
        $this->planFeatures = array_values($this->planFeatures); // Reindex the array
    }

    public function getFilteredFeaturesProperty()
    {
        // Get all features
        $allFeatures = Feature::all();

        // Filter out features that are already added to the plan
        return $allFeatures->reject(function ($feature) {
            return in_array($feature->id, array_column($this->planFeatures, 'id'));
        });
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:plans',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'interval' => 'required|in:month,year',
            'planFeatures' => 'required|array|min:1',
        ]);

        $plan = Product::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $price = Price::create([
            'product' => $plan->id,
            'unit_amount' => $this->amount * 100,
            'currency' => 'usd',
            'recurring' => [
                'interval' => $this->interval,
            ],
        ]);

        try {
            $createdPlan = Plan::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'amount' => $this->amount,
                'interval' => $this->interval,
                'product_id' => $plan->id,
                'price_id' => $price->id,
            ]);

            foreach ($this->planFeatures as $feature) {
                $createdPlan->features()->attach($feature['id'], ['value' => $feature['value']]);
            }

            $this->dispatch('notify',
                variant: 'success',
                message: 'Plan created successfully.',
            );

        } catch (\Exception $e) {
            Log::error($e);

            $this->dispatch('notify',
                variant: 'danger',
                message: 'Something went wrong',
            );
        }

        $this->reset();
    }

    public function delete(Plan $plan)
    {
        try {
            $plan->delete();
            $this->dispatch('notify',
                variant: 'success',
                message: 'Plan deleted successfully.',
            );
        } catch (\Exception $e) {
            Log::error($e);

            $this->dispatch('notify',
                variant: 'danger',
                message: 'Something went wrong! Please try again.',
            );
        }
    }

    public function updateStatus(Plan $plan)
    {
        Product::update(
            $plan->product_id,
            ['active' => !$plan->status]
        );

        Price::update(
            $plan->price_id,
            ['active' => !$plan->status]
        );

        $plan->update([
            'status' => !$plan->status,
        ]);

        $this->dispatch('notify',
            variant: 'success',
            message: 'Plan status updated successfully.',
        );
    }

    public function with()
    {
        return [
            'plans' => Plan::latest()->paginate(10),
            'features' => $this->filteredFeatures, // Use filtered features
        ];
    }
}; ?>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Plans') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white shadow-sm overflow-hidden rounded-lg p-6 mb-6">
            <div class="mb-4">
                <h2 class="font-semibold text-md text-gray-800 leading-tight">
                    Create a plan
                </h2>
            </div>
            <form wire:submit.prevent="store" class="space-y-2">
                <flux:input label="Name" placeholder="Plan name" wire:model="name"/>

                <flux:textarea label="Description" placeholder="Plan description" wire:model="description"/>

                <flux:input label="Amount" placeholder="9.99" type="number" step="0.01" wire:model="amount"/>

                <flux:select label="Interval" wire:model="interval">
                    <flux:select.option value="month">Monthly</flux:select.option>
                    <flux:select.option value="year">Yearly</flux:select.option>
                </flux:select>

                <div class="mt-4 space-y-4 max-w-sm">
                    <label class="block text-sm font-medium text-gray-700">Features</label>
                    <div class="px-4">
                        <div class="flex space-x-4 items-end">
                            <flux:select label="Feature" wire:model="feature">
                                <flux:select.option value="">Select a feature</flux:select.option>
                                @foreach($features as $feature)
                                    <flux:select.option value="{{$feature->id}}">{{$feature->name}}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:input label="Value" placeholder="Value" wire:model="value"/>
                            <div>
                                <flux:button wire:click="addFeature" variant="primary">Add</flux:button>
                            </div>
                        </div>
                        <ul class="mt-4 space-y-2">
                            @forelse($planFeatures as $index => $feature)
                                <li class="flex justify-between items-center">
                                    <span>{{ $feature['value'] }} {{ $feature['name'] }}</span>
                                    <flux:button icon="x-mark" wire:click="removeFeature({{ $index }})" variant="danger" size="sm"/>
                                </li>
                            @empty
                                <li class="text-gray-500">No features added.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="flex mt-4">
                    <flux:spacer/>
                    <flux:button type="submit" variant="primary">Create</flux:button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm overflow-hidden rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Price
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Interval
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($plans as $plan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{$plan->name}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${{number_format($plan->amount, 2)}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ucfirst($plan->interval)}}ly</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{$plan->active ? 'green' : 'red'}}-100 text-{{$plan->active ? 'green' : 'red'}}-800">
                            {{$plan->status ? 'Active' : 'Inactive'}}
                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:button variant='ghost'
                                         wire:click='updateStatus({{$plan}})'>
                                {{$plan->status ? 'Deactivate' : 'Activate'}}
                            </flux:button>
                            <flux:button icon="trash" variant="danger"
                                         wire:confirm="Are you sure you want to delete this plan?"
                                         wire:click='delete({{$plan}})'>
                                Delete
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" colspan="5">
                            <div class="text-sm text-gray-500">No plans found.</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap">
                        {{$plans->links()}}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
