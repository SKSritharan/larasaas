<div>
    <div class="z-50 fixed top-1 right-0 p-4 space-y-2" x-data="{show: true}">
        @if(session()->has('success'))
            <div class="font-bold text-green-500 bg-green-100 p-2 rounded-lg" role="alert" x-show="show" x-cloak x-init="setTimeout(() => show = false, 5000)">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="font-bold text-red-500 bg-red-100 p-2 rounded-lg" role="alert" x-show="show" x-cloak x-init="setTimeout(() => show = false, 5000)">
                {{ session()->get('error') }}
            </div>
        @endif
    </div>
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
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Price
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Interval
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                     wire:confirm="Are you sure you want to delete this site?"
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
