@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order: #{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @include('admin.message')

            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <!-- Billing Address -->
                                <div class="col-sm-6 invoice-col">
                                    <h1 class="h5 mb-3">Billing Address</h1>
                                    <address>
                                        <strong>{{ $order->billingAddress->name }}</strong><br>
                                        {{ $order->billingAddress->address_1 }}<br>
                                        {{ $order->billingAddress->address_2 }}<br>
                                        City: {{ $order->billingAddress->city }}<br>
                                        Zip: {{ $order->billingAddress->zip }}<br>
                                        Country: {{ $order->billingAddress->country }}<br>
                                        Phone: {{ $order->billingAddress->phone }}<br>
                                        Email: {{ $order->billingAddress->email }}
                                    </address>
                                </div>

                                <!-- Shipping Address -->
                                <div class="col-sm-6 invoice-col">
                                    <h1 class="h5 mb-3">Shipping Address</h1>
                                    <address>
                                        <strong>{{ $order->shippingAddress->name }}</strong><br>
                                        {{ $order->shippingAddress->address_1 }}<br>
                                        {{ $order->shippingAddress->address_2 }}<br>
                                        City: {{ $order->shippingAddress->city }}<br>
                                        Zip: {{ $order->shippingAddress->zip }}<br>
                                        Country: {{ $order->shippingAddress->country }}<br>
                                        Phone: {{ $order->shippingAddress->phone }}<br>
                                        Email: {{ $order->shippingAddress->email }}
                                    </address>
                                </div>

                                <div class="col-sm-12">
                                    <h1 class="h5 mb-3">Order Status</h1>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="lead">Order Status: <span class="badge badge-success">{{ $order->status }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td>Rs.{{ number_format($item->product_price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rs.{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>Rs. 0.00</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <form action="{{ route('orders.changeOrderStatus', $order->id) }}" method="POST" id="ChangeOrderStatusForm">
                            @csrf
                            <div class="card-body">
                                <h2 class="h4 mb-3">Order Status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="In Progress" {{ $order->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">View Invoice</h2>
                            <div class="mb-3">
                                <a href="{{ route('admin.orders.viewInvoice', $order->id) }}" target="_blank" class="btn btn-primary">Download Invoice</a>
                                <a href="{{ route('orders.printInvoice', $order->id) }}" class="btn btn-success">Print Invoice</a>
                            </div>
                        </div>
                    </div>

                    <!-- Download Modal -->
                    <div id="downloadModal" style="display:none;">
                        <div class="modal-content">
                            <h3>Invoice sent to printer successfully!</h3>
                            <p>You can download the invoice PDF now.</p>
                            <button id="downloadInvoice" class="btn btn-success">Download Invoice</button>
                            <button id="closeModal" class="btn btn-danger">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Print invoice via PrintNode API
            document.querySelector('.btn-success').addEventListener('click', async (event) => {
                event.preventDefault(); // Prevent the default link behavior
                const printUrl = event.target.href;

                try {
                    const response = await fetch(printUrl, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                    });

                    if (response.ok) {
                        alert('Invoice sent to printer successfully!');
                        document.getElementById('downloadModal').style.display =
                        'block'; // Show modal for download
                    } else {
                        const errorData = await response.json();
                        alert(
                            `Failed to send invoice to printer: ${errorData.error || 'Unknown error'}`);
                    }
                } catch (error) {
                    console.error('Error printing invoice:', error);
                    alert('Error printing invoice. Please check the console for details.');
                }
            });

            // Handle invoice download
            document.getElementById('downloadInvoice').addEventListener('click', () => {
                const orderId = {{ $order->id }};
                const downloadUrl = `{{ route('admin.orders.viewInvoice', $order->id) }}`;
                const downloadLink = document.createElement('a');
                downloadLink.href = downloadUrl;
                downloadLink.download = `invoice-${orderId}.pdf`;
                downloadLink.click();
            });

            // Close modal
            document.getElementById('closeModal').addEventListener('click', () => {
                document.getElementById('downloadModal').style.display = 'none';
            });

            // Handle order status form submission via AJAX
            const form = document.getElementById('ChangeOrderStatusForm');
            if (form) {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const formData = new FormData(form);
                    const selectedStatus = formData.get('status');

                    fetch("{{ route('orders.changeOrderStatus', $order->id) }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                window.location.href = "{{ route('orders.detail', $order->id) }}";
                            } else {
                                alert(data.message || 'Failed to update order status.');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating order status:', error);
                            alert('Error updating order status. Please check the console for details.');
                        });


                });
            }
        });
    </script>
@endsection
