<?php

use App\Models\Feature;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')] class extends Component {
    public $name = '';
    public $description = '';

    public function store()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
        ]);

        try {
            Feature::create($validated);


            $this->dispatch('notify',
                variant: 'success',
                message: 'Feature created successfully.',
            );

        } catch (\Exception $e) {
            Log::error($e);

            $this->dispatch('notify',
                varient: 'danger',
                message: 'Something went wrong',
            );
        }

        $this->reset();
    }

    public function delete(Feature $feature)
    {
        try {
            $feature->delete();
            $this->dispatch('notify',
                variant: 'success',
                message: 'Feature deleted successfully.',
            );
        } catch (\Exception $e) {
            Log::error($e);
            $this->dispatch('notify',
                variant: 'danger',
                message: 'Something went wrong',
            );
        }
    }

    public function with()
    {
        return [
            'features' => Feature::paginate(10),
        ];
    }
}; ?>
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Features') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white shadow-sm overflow-hidden rounded-lg p-6 mb-6">
            <div class="mb-4">
                <h2 class="font-semibold text-md text-gray-800 leading-tight">
                    Create a feature
                </h2>
            </div>
            <form wire:submit.prevent="store" class="space-y-2">
                <flux:input label="Name" placeholder="Feature name" wire:model="name"/>

                <flux:textarea label="Description" placeholder="Feature description" wire:model="description"/>

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
                        Description
                    </th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($features as $feature)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{$feature->name}}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{$feature->description}}
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <flux:button icon="trash" variant="danger"
                                         wire:confirm="Are you sure you want to delete this feature?"
                                         wire:click='delete({{$feature}})'>
                                Delete
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap" colspan="5">
                            <div class="text-sm text-gray-500">No features found.</div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap">
                        {{$features->links()}}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
