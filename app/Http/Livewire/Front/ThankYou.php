<?php

declare(strict_types=1);

namespace App\Http\Livewire\Front;

use App\Models\Order;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ThankYou extends Component
{
    public $order;

    public function mount($order)
    {
        // Eager load 'products' relation and other needed relations
        $this->order = Order::with(['products', 'user', 'address'])->findOrFail($order->id);
    }

    public function render(): View|Factory
    {
        return view('livewire.front.thank-you');
    }
}
