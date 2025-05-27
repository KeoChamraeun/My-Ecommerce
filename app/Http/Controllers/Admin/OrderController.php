<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Handled by Livewire component
        return view('admin.order.index');
    }

    public function show(Order $order)
    {
        return view('admin.order.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.order.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', \App\Enums\OrderStatus::getValues()),
            'total_qty' => 'nullable|integer|min:0',
            'total_cost' => 'nullable|numeric|min:0',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders')->with('success', __('Order updated successfully.'));
    }
}
