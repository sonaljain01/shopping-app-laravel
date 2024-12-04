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
                                    </address>
                                </div>

                                <div class="col-sm-12">
                                    <h1 class="h5 mb-3">Order Status</h1>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="lead">Order Status: <span
                                                    class="badge badge-success">{{ $order->status }}</span></p>
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
                                            <td>{{ $order->currency_code }}{{ number_format($item->product_price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $order->currency_code }}{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>{{ $order->currency_code }} {{ number_format($order->total_amount, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Tax:</th>
                                        <td>{{ $order->currency_code }} {{ number_format($order->tax, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>{{ $order->currency_code }} 0.00</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td>{{ $order->currency_code }} {{ number_format($order->grand_total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">View Invoice</h2>
                            <div class="mb-3">
                                <a href="{{ route('admin.orders.viewInvoice', $order->id) }}" target="_blank"
                                    class="btn btn-primary">Download Invoice</a>
                                <a href="{{ route('orders.printInvoice', $order->id) }}" class="btn btn-success">Print
                                    Invoice</a>
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

                    <div class="card">
                        <form action="{{ route('orders.changeOrderStatus', $order->id) }}" method="POST"
                            id="ChangeOrderStatusForm">
                            @csrf
                            <div class="card-body">
                                <h2 class="h4 mb-3">Order Status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="In Progress"
                                            {{ $order->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="modal modal-blur fade" id="modal-pickup" tabindex="-1" role="dialog"
                        aria-labelledby="modal-pickup-label" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-pickup-label">Select Pickup Address</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="pickup" class="col-form-label required">Pickup Address</label>
                                        <select class="form-select" id="pickup" name="pickup" required>
                                            @if (isset($pickupAddresses) && $pickupAddresses->isNotEmpty())
                                                @foreach ($pickupAddresses as $address)
                                                    <option value="{{ $address->id }}">{{ $address->tag }}</option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>No pickup address found</option>
                                            @endif
                                        </select>
                                        <small id="pickupHelp" class="form-text text-muted">
                                            Choose a pickup address for the order.
                                        </small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" id="btn-confirm-pickup" class="btn btn-primary"
                                        {{ isset($pickupAddresses) && $pickupAddresses->isNotEmpty() ? '' : 'disabled' }}>
                                        Confirm Pickup Address
                                    </button>
                                </div>
                            </div>
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
                            `Failed to send invoice to printer: ${errorData.error || 'Unknown error'}`
                        );
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
        });


        document.addEventListener('DOMContentLoaded', () => {
            const statusDropdown = document.getElementById('status');
            const modalPickup = new bootstrap.Modal(document.getElementById('modal-pickup'));
            const pickupField = document.getElementById('pickup');
            const changeOrderForm = document.getElementById('ChangeOrderStatusForm');
            const confirmPickupButton = document.getElementById('btn-confirm-pickup');

            let pickupConfirmed = false;

            // Handle status change and open modal if status is 'shipped'
            statusDropdown.addEventListener('change', (e) => {
                if (e.target.value === 'shipped') {
                    // Reset pickup confirmation when status is changed to 'shipped'
                    pickupConfirmed = false;
                    modalPickup.show();
                }
            });

            // Confirm pickup address and mark it as confirmed
            confirmPickupButton.addEventListener('click', () => {
                if (pickupField.value) {
                    pickupConfirmed = true;
                    modalPickup.hide();
                } else {
                    alert("Please select a pickup address.");
                }
            });

            // Handle form submission via AJAX
            changeOrderForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Ensure 'shipped' status has a confirmed pickup address
                if (statusDropdown.value === 'shipped' && !pickupConfirmed) {
                    alert("Please confirm a pickup address before changing status to 'Shipped'.");
                    return;
                }

                // Attach the pickup address dynamically if needed
                if (statusDropdown.value === 'shipped' && pickupField.value) {
                    const hiddenPickupInput = document.createElement('input');
                    hiddenPickupInput.type = 'hidden';
                    hiddenPickupInput.name = 'pickup';
                    hiddenPickupInput.value = pickupField.value;
                    changeOrderForm.appendChild(hiddenPickupInput);
                }

                // Submit the form via AJAX
                const formData = new FormData(changeOrderForm);

                const res = await fetch(changeOrderForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                })

                const data = await res.json();
                console.log(data);
            });
        });
    </script>
@endsection
