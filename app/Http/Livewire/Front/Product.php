<?php

declare(strict_types=1);

namespace App\Http\Livewire\Front;

use App\Models\Product as ProductModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Product extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function render(): View|Factory
    {
        $product = ProductModel::find($this->productId);
        return view('livewire.front.product', [
            'product' => $product,
        ]);
    }
}
