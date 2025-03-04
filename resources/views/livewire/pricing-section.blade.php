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
                <div
                    class="relative rounded-3xl bg-zinc-900 p-8 ring-1 shadow-xl ring-zinc-900/10 sm:p-10 hover:shadow-2xl hover:-translate-y-2 transition">
                    <h3 class="text-base font-semibold text-[#FF2D20]">{{ $plan->name }}</h3>
                    <p class="mt-4 flex items-baseline gap-x-2">
                        <span class="text-5xl font-semibold tracking-tight text-white">${{ $plan->amount }}</span>
                        <span class="text-base text-zinc-400">/{{ $plan->interval }}</span>
                    </p>
                    <p class="mt-6 text-base text-zinc-300">{{ $plan->description }}</p>
                    <ul role="list" class="mt-8 space-y-3 text-sm text-zinc-300 sm:mt-10">
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-[#FF2D20]" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Unlimited products
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-[#FF2D20]" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Unlimited subscribers
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-[#FF2D20]" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Advanced analytics
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-[#FF2D20]" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Dedicated support representative
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-[#FF2D20]" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Marketing automations
                        </li>
                        <li class="flex gap-x-3">
                            <svg class="h-6 w-5 flex-none text-[#FF2D20]" viewBox="0 0 20 20" fill="currentColor"
                                 aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Custom integrations
                        </li>
                    </ul>
                    <a href="{{route('checkout', $plan->slug)}}"
                       class="mt-8 block rounded-md bg-[#FF2D20] px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-white hover:text-[#FF2D20] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#FF2D20] sm:mt-10">
                        Get Started Now
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
