<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.username', 'users.email');
        $orders = $orders->leftJoin('users', 'users.id', 'orders.user_id');

        if ($request->get('keyword') != "") {
            $orders = $orders->where('users.username', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('users.email', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('orders.id', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('orders.phone', 'like', '%' . $request->keyword . '%');
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.list', [
            'orders' => $orders
        ]);
    }

    public function detail($orderId)
    {
        $order = Order::select('orders.*', 'billing_addresses.*')
            ->where('orders.id', $orderId)
            ->leftJoin('billing_addresses', 'orders.billing_address_id', '=', 'billing_addresses.id')
            ->first();

        $orderItems = OrderItem::select('order_items.*', 'products.*')
            ->where('order_id', $orderId)
            ->rightJoin('products', 'order_items.product_id', '=', 'products.id')
            ->get();


        // Check if order exists
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function changeOrderStatus(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->save();

        session()->flash('success', 'Order status updated successfully');
        return redirect()->route('orders.detail', $orderId)->with('success', 'Order status updated successfully');
    }

    public function downloadInvoice($orderId)
    {
        $order = Order::select('orders.*', 'billing_addresses.*')
            ->where('orders.id', $orderId)
            ->leftJoin('billing_addresses', 'orders.billing_address_id', '=', 'billing_addresses.id')
            ->first();

        // Retrieve order items with product details
        $orderItems = OrderItem::select('order_items.*', 'products.*')
            ->where('order_id', $orderId)
            ->rightJoin('products', 'order_items.product_id', '=', 'products.id')
            ->get();

        // Check if order exists
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        // Load the PDF view
        $pdf = Pdf::loadView('invoices.invoice', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);

        // Download the PDF with a filename based on the order ID
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }
}
