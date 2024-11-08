<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

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
        // $order = Order::join('billing_addresses', 'orders.billing_address_id', '=', 'billing_addresses.id')
        // ->where('orders.id', $orderId)
        // ->select(
        //     'orders.id as orderId',
        //     'orders.total_amount',
        //     'orders.status',
        //     'billing_addresses.username',
        //     'billing_addresses.address_1',
        //     'billing_addresses.address_2',
        //     'billing_addresses.city',
        //     'billing_addresses.zip',
        //     'billing_addresses.phone',
        //     'billing_addresses.email',
        //     'billing_addresses.country'
        // )
        // ->first();

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
}
