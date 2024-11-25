<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">Billing Address</h5>
        <form action="{{ route('billing.address.save') }}" method="POST">
            @csrf
            <!-- Full Name -->
            <div class="form-group">
                <label for="username">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="{{ old('username', $billingAddress->username ?? '') }}" required>
            </div>

            <!-- Email -->
            {{-- <div class="form-group">
                <label for="email">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="{{ old('email', $billingAddress->email ?? '') }}" required>
            </div> --}}

            <!-- Company -->
            <div class="form-group">
                <label for="company">Company (Optional)</label>
                <input type="text" class="form-control" id="company" name="company" 
                       value="{{ old('company', $billingAddress->company ?? '') }}">
            </div>

            <!-- Address 1 -->
            <div class="form-group">
                <label for="address_1">Address Line 1 <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="address_1" name="address_1" 
                       value="{{ old('address_1', $billingAddress->address_1 ?? '') }}" required>
            </div>

            <!-- Address 2 -->
            <div class="form-group">
                <label for="address_2">Address Line 2 (Optional)</label>
                <input type="text" class="form-control" id="address_2" name="address_2" 
                       value="{{ old('address_2', $billingAddress->address_2 ?? '') }}">
            </div>

            <!-- City -->
            <div class="form-group">
                <label for="city">City <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="city" name="city" 
                       value="{{ old('city', $billingAddress->city ?? '') }}" required>
            </div>

            <!-- ZIP/Postal Code -->
            <div class="form-group">
                <label for="zip">ZIP/Postal Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="zip" name="zip" 
                       value="{{ old('zip', $billingAddress->zip ?? '') }}" required>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label for="phone">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="phone" name="phone" 
                       value="{{ old('phone', $billingAddress->phone ?? '') }}" required>
            </div>

            <!-- Additional Information -->
            <div class="form-group">
                <label for="additional_information">Additional Information (Optional)</label>
                <textarea class="form-control" id="additional_information" name="additional_information" rows="3">{{ old('additional_information', $billingAddress->additional_information ?? '') }}</textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary btn-block">Save Billing Address</button>
        </form>
    </div>
</div>
