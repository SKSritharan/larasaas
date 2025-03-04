<?php

use App\Models\Plan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

new #[Layout('layouts.register')] class extends Component {
    public string $name = '';
    public string $email = '';
    public Plan $plan;
    public string $password = '';
    public string $password_confirmation = '';

    public function mount($plan)
    {
        try {
            $this->plan = $plan;
        } catch (Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        $user->assignRole('user');

        Auth::login($user);

        redirect(route('checkout', $this->plan));
    }
}; ?>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

    <div class="px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <form wire:submit="register">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')"/>
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                              autofocus autocomplete="name"/>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')"/>
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                              required
                              autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')"/>

                <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>

                <x-text-input wire:model="password_confirmation" id="password_confirmation"
                              class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation" required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('login') }}" wire:navigate>
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <div class="px-6 py-4 bg-zinc-900 shadow-md overflow-hidden sm:rounded-lg">
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
    </div>
</div>
