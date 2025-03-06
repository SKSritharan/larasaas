<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="p-6 bg-white shadow-sm rounded-lg">
                <livewire:billing.manage-billing/>
            </div>
            <div class="p-6 bg-white shadow-sm rounded-lg">
                <livewire:billing.change-plan-form/>
            </div>
        </div>
    </div>
</x-app-layout>
