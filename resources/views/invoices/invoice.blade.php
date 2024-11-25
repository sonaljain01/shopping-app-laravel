<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice #{{ $order->id }}</h1>
        <p>Order Date: {{ $order->created_at->format('d M Y') }}</p>
        <p>Shipped By: {{ $order->billingAddress->name ?? 'N/A' }}</p>
        <p>Billing Address: {{ $order->billingAddress->address_1 }}, {{ $order->billingAddress->address_2 }}, {{ $order->billingAddress->city }}, {{ $order->billingAddress->country }}</p>
        <p>Zip: {{ $order->billingAddress->zip }}</p>
        <p>Phone: {{ $order->billingAddress->phone }}</p>
    </div>

    <div class="header">
        <p>Shipping To: {{ $order->shippingAddress->name ?? 'N/A' }}</p>
        <p>Shipping Address: {{ $order->shippingAddress->address_1 }}, {{ $order->shippingAddress->address_2 }}, {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->country }}</p>
        <p>Zip: {{ $order->shippingAddress->zip }}</p>
        <p>Phone: {{ $order->shippingAddress->phone }}</p>
    </div>

    <div class="details">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->title }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs. {{ number_format($item->price, 2) }}</td>
                        <td>Rs. {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Grand Total: Rs. {{ number_format($order->total_amount, 2) }}</strong></p>
    </div>
</body>
</html>

