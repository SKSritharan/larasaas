@props([
    'plan' => null,
    'showButton' => true,
    'isActive' => false,
])

<div class="relative rounded-3xl bg-zinc-900 p-8 ring-1 shadow-xl ring-zinc-900/10 sm:p-10
            {{!$isActive ? 'hover:shadow-2xl hover:-translate-y-2 transition': ''}}
            {{ $isActive ? 'border-4 border-green-500' : '' }}">

    @if($isActive)
        <div class="absolute top-4 right-4">
            <div class="rounded-full bg-success/15 p-0.5 text-success" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     class="size-5" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    @endif

    <h3 class="text-base font-semibold text-[#FF2D20]">{{ $plan->name }}</h3>
    <p class="mt-4 flex items-baseline gap-x-2">
        <span class="text-5xl font-semibold tracking-tight text-white">${{ $plan->amount }}</span>
        <span class="text-base text-zinc-400">/{{ $plan->interval }}</span>
    </p>
    <p class="mt-6 text-base text-zinc-300">{{ $plan->description }}</p>
    <ul role="list" class="mt-8 space-y-3 text-sm text-zinc-300 sm:mt-10">
        @php
            $allFeatures = \App\Models\Feature::all();
            $planFeatures = $plan->features->pluck('id')->toArray();
        @endphp

        @foreach($allFeatures as $feature)
            <li class="flex gap-x-3">
                @if(in_array($feature->id, $planFeatures))
                    <svg class="h-6 w-5 flex-none text-green-600" viewBox="0 0 20 20" fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                              clip-rule="evenodd"/>
                    </svg>
                    @if($plan->features->find($feature->id)->pivot->value)
                        <span>{{ $plan->features->find($feature->id)->pivot->value }}</span>
                    @endif
                    {{ $feature->name }}
                @else
                    <svg class="h-6 w-5 flex-none text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>

                    {{ $feature->name }}
                @endif
            </li>
        @endforeach
    </ul>

    @if($showButton)
        <a href="{{ route('checkout', $plan->slug) }}"
           class="mt-8 block rounded-md bg-[#FF2D20] px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-white hover:text-[#FF2D20] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#FF2D20] sm:mt-10">
            Get Started Now
        </a>
    @endif
</div>
