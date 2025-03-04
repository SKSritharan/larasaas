<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class CheckoutController extends Controller
{
    public function checkout(string $planSlug)
    {
        $plan = Plan::where('slug', $planSlug)->first();

        if (!Auth::check()) {
            return redirect()->route('register', $planSlug);
        }

        if (auth()->user()->subscribedToPrice($plan->price_id)) {
            return redirect()->route('dashboard')->with('error', 'You are already subscribed to this plan.');
        }

        $user = auth()->user();

        return $user
            ->newSubscription('default', $plan->price_id)
            ->checkout([
                'success_url' => route('checkout-success', $planSlug),
                'cancel_url' => route('home'),
            ]);
    }

    public function success(Request $request, string $planSlug)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Invalid session ID.');
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return redirect()->route('home')->with('error', 'Payment was not successful.');
        }

        return redirect()->route('dashboard')->with('success', 'Subscription successful!');
    }
}
