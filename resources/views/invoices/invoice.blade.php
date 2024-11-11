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
        <p>Customer: {{ $order->username ?? 'N/A' }}</p>
        <p>Billing Address: {{ $order->address_1 }}, {{ $order->address_2 }}, {{ $order->city }}, {{ $order->country }}</p>
        <p>Zip: {{ $order->zip }}</p>
        <p>Phone: {{ $order->phone }}</p>
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
                @foreach ($orderItems as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs. {{ number_format($item->price, 2) }}</td>
                        <td>Rs. {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Grand Total: Rs. {{ number_format($order->total_amount, 2) }}</strong></p>
    </div>
</body>
</html>
