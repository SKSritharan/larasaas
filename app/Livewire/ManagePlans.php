<?php

namespace App\Livewire;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class ManagePlans extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $amount;
    public $interval = 'month';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'interval' => 'required|in:month,year',
    ];

    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    public function store()
    {
        $this->validate();

        $plan = Product::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $price = Price::create([
            'product' => $plan->id,
            'unit_amount' => $this->amount,
            'currency' => 'usd',
            'recurring' => [
                'interval' => $this->interval,
            ],
        ]);

        try {
            Plan::create([
                'name' => $this->name,
                'description' => $this->description,
                'amount' => $this->amount,
                'interval' => $this->interval,
                'product_id' => $plan->id,
                'price_id' => $price->id,
            ]);
            session()->flash('success', 'Plan created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong! Please try again.');
        }

        $this->reset();
    }

    public function delete(Plan $plan)
    {
        try {
            $plan->delete();
            session()->flash('success', 'Plan deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong! Please try again.');
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
    }

    public function render()
    {
        return view('livewire.manage-plans')
            ->with([
                'plans' => Plan::latest()->paginate(10),
            ]);
    }
}
