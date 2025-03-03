<?php

namespace App\Livewire;

use App\Models\Plan;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class ManagePlans extends Component
{
    use WithPagination;

    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'interval' => 'required|in:day,week,month,year',
        ]);

        // Create product
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Create price
        $price = Price::create([
            'product' => $product->id,
            'unit_amount' => $request->amount * 100,
            'currency' => 'usd',
            'recurring' => [
                'interval' => $request->interval,
            ],
        ]);

        // Save plan to database
        Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'amount' => $request->amount * 100,
            'interval' => $request->interval,
            'product_id' => $product->id,
            'price_id' => $price->id,
        ]);
    }

    public function render()
    {
        return view('livewire.manage-plans', [
            'plans' => Plan::paginate(10),
        ]);
    }
}
