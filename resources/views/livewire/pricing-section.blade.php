<div class="relative isolate" id="pricing">
    <div class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl" aria-hidden="true">
        <div class="mx-auto aspect-1155/678 w-[72.1875rem] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"
             style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
    </div>
    <div class="mx-auto max-w-4xl text-center">
        <p class="mt-2 text-4xl font-bold tracking-tight text-balance text-zinc-900">Choose the right plan for you</p>
    </div>
    <p class="mx-auto mt-6 max-w-2xl text-center text-xl font-medium text-pretty text-zinc-600">
        Choose an affordable plan that’s packed with the best features for engaging your audience, creating customer
        loyalty, and driving sales.
    </p>

    <div class="flex justify-center mt-8">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" wire:model.live="filter" class="sr-only peer">
            <div
                class="relative w-11 h-6 bg-zinc-400 peer-focus:outline-none peer-focus:ring-[#FF2D20] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#FF2D20]"></div>
        </label>
        <span class="ms-3 text-xl font-bold text-[#FF2D20]">Yearly</span>
    </div>

    <div class="mx-auto mt-16 max-w-6xl">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
                <x-single-plan :plan="$plan" />
            @endforeach
        </div>
    </div>
</div>
