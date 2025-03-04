<?php

namespace App\Livewire;

use App\Models\Plan;
use Illuminate\View\View;
use Livewire\Component;

class PricingSection extends Component
{
    public $filter = 0;
    public $interval='month';

    public function updatedFilter(): void
    {
        match ($this->filter) {
            false => $this->interval = 'month',
            true => $this->interval = 'year',
        };
    }

    public function render():View
    {
        return view('livewire.pricing-section', [
            'plans' => Plan::where([
                'status' => 1,
                'interval' => $this->interval
            ])->get()
        ]);
    }
}
