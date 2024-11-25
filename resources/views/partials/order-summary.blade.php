<div class="card mb-4 gray">
    <div class="card-body">
        <h5 class="mb-4">Order Summary</h5>
        <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
            <!-- Subtotal -->
            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                <span>Subtotal</span>
                <span class="ml-auto text-dark ft-medium">Rs.{{ number_format($subtotal, 2) }}</span>
            </li>
            
            <!-- Tax -->
            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                <span>Tax</span>
                <span class="ml-auto text-dark ft-medium">Rs.{{ number_format($tax, 2) }}</span>
            </li>
            
            <!-- Total -->
            <li class="list-group-item d-flex text-dark fs-sm ft-regular">
                <span>Total</span>
                <span class="ml-auto text-dark ft-medium">Rs.{{ number_format($total, 2) }}</span>
            </li>
            
            <!-- Shipping Notice -->
            <li class="list-group-item fs-sm text-center">
                Shipping cost calculated at Checkout *
            </li>
        </ul>
    </div>
</div>
