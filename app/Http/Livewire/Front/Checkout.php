<?php

declare(strict_types=1);

namespace App\Http\Livewire\Front;

use App\Mail\CheckoutMail;
use App\Mail\CustomerRegistrationMail;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Shipping;
use App\Models\Product;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Checkout extends Component
{
    use LivewireAlert;

    public $listeners = [
        'checkout' => 'checkout',
        'checkoutCartUpdated' => '$refresh',
        'confirmed',
    ];

    public $payment_method = 'cash';
    public $shipping_cost;
    public $first_name;
    public $last_name;
    public $email;
    public $address;
    public $city;
    public $shipping;
    public $country = 'Maroc';
    public $phone;
    public $password;
    public $total;
    public $order_status;
    public $shipping_id;
    public $cartTotal;
    public $productId;

    public function confirmed()
    {
        Cart::instance('shopping')->remove($this->productId);
        $this->emit('cartCountUpdated');
        $this->emit('checkoutCartUpdated');
    }

    public function getCartItemsProperty()
    {
        return Cart::instance('shopping')->content();
    }

    public function getSubTotalProperty()
    {
        return Cart::instance('shopping')->subtotal();
    }

    public function checkout()
    {
        $this->validate([
            'shipping_id' => 'required',
            'first_name' => 'required',
            'phone' => 'required',
            'email' => !auth()->check() ? 'required|email|unique:users,email' : 'nullable',
            'password' => !auth()->check() ? 'required|min:8' : 'nullable',
        ]);

        if (Cart::instance('shopping')->count() === 0) {
            $this->alert('error', __('Your cart is empty'));
            return;
        }

        $shipping = Shipping::find($this->shipping_id);
        if (!$shipping) {
            $this->alert('error', __('Invalid shipping method'));
            return;
        }

        if (!auth()->check()) {
            $user = User::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'city' => $this->city,
                'country' => $this->country,
                'address' => $this->address,
                'phone' => $this->phone,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            Mail::to($user->email)->queue(new CustomerRegistrationMail($user));
            Auth::login($user);
        } else {
            $user = auth()->user();
        }

        $order = Order::create([
            'reference' => Order::generateReference(),
            'shipping_id' => $this->shipping_id,
            'delivery_method' => $shipping->title,
            'payment_method' => $this->payment_method,
            'shipping_cost' => $shipping->cost,
            'first_name' => $this->first_name,
            'shipping_name' => $this->first_name . '-' . $this->last_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'shipping_address' => $this->address,
            'city' => $this->city,
            'shipping_city' => $this->city,
            'phone' => $this->phone,
            'shipping_phone' => $this->phone,
            'total' => $this->cartTotal,
            'subtotal' => Cart::instance('shopping')->subtotal(),
            'tax' => 0,
            'user_id' => $user->id,
            'order_status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
        ]);

        $cartItems = Cart::instance('shopping')->content();
        if ($cartItems->isEmpty()) {
            \Log::error('Cart is empty when creating OrderProduct records for order ID: ' . $order->id);
        }

        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            if (!$product) {
                \Log::error('Product not found for product_id: ' . $item->id);
                continue;
            }

            $orderProduct = new OrderProduct([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'qty' => $item->qty,
                'price' => $item->price,
                'user_id' => $user->id,
                'total' => $item->price * $item->qty,
            ]);

            $orderProduct->save();
        }

        $order->load('order_products.product');

        Mail::to($user->email)->queue(new CheckoutMail($order, $user));

        Cart::instance('shopping')->destroy();

        $this->alert('success', __('Order placed successfully!'));

        return redirect()->route('front.thankyou', ['order' => $order->id]);
    }

    public function updatedShippingId($value)
    {
        if ($value) {
            $this->shipping = Shipping::find($value);
            $this->updateCartTotal();
        }
    }

    public function updateCartTotal()
    {
        if ($this->shipping_id) {
            $shipping = Shipping::find($this->shipping_id);
            $cost = $shipping->cost ?? 0;
            $total = Cart::instance('shopping')->total();

            $this->cartTotal = $total + $cost;
        } else {
            $this->cartTotal = Cart::instance('shopping')->total();
        }
    }

    public function decreaseQuantity($rowId)
    {
        $cartItem = Cart::instance('shopping')->get($rowId);
        if ($cartItem) {
            $qty = $cartItem->qty - 1;
            if ($qty >= 1) {
                Cart::instance('shopping')->update($rowId, $qty);
            }
            $this->emit('checkoutCartUpdated');
        }
    }

    public function increaseQuantity($rowId)
    {
        $cartItem = Cart::instance('shopping')->get($rowId);
        if ($cartItem) {
            $qty = $cartItem->qty + 1;
            Cart::instance('shopping')->update($rowId, $qty);
            $this->emit('checkoutCartUpdated');
        }
    }

    public function removeFromCart($rowId)
    {
        $this->productId = $rowId;

        $this->confirm(
            __('Remove from cart?'),
            [
                'position' => 'center',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Confirm',
                'onConfirmed' => 'confirmed',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancel',
            ]
        );
    }

    public function getShippingsProperty()
    {
        return Shipping::select('id', 'title', 'cost')->get();
    }

    public function getCartTotalProperty()
    {
        return Cart::instance('shopping')->total();
    }

    public function render(): View|Factory
    {
        return view('livewire.front.checkout', [
            'cartItems' => $this->cartItems,
            'shippings' => $this->shippings,
            'cartTotal' => $this->cartTotal,
        ]);
    }
}
