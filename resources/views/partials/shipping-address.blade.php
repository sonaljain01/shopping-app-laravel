<div class="row mb-4">
    <div class="col-12">
        <input id="sameAsBilling" class="checkbox-custom" name="same_as_billing" type="checkbox" onchange="toggleShippingAddress()">
        <label for="sameAsBilling" class="checkbox-custom-label">Shipping address is the same as billing address</label>
    </div>
</div>

<div id="shippingAddressFields" style="display: none;">
    <h5 class="mb-4 ft-medium">Shipping Details</h5>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="shipping_name">Name <span class="text-danger">*</span></label>
                <input type="text" id="shipping_name" name="shipping_name" class="form-control" placeholder="Name">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="shipping_company">Company</label>
                <input type="text" id="shipping_company" name="shipping_company" class="form-control" placeholder="Company">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_address_1">Address Line 1 <span class="text-danger">*</span></label>
                <input type="text" id="shipping_address_1" name="shipping_address_1" class="form-control" placeholder="Address Line 1">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_address_2">Address Line 2</label>
                <input type="text" id="shipping_address_2" name="shipping_address_2" class="form-control" placeholder="Address Line 2">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_zip">Zip Code <span class="text-danger">*</span></label>
                <input type="text" id="shipping_zip" name="shipping_zip" class="form-control" placeholder="Zip Code">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_city">City <span class="text-danger">*</span></label>
                <input type="text" id="shipping_city" name="shipping_city" class="form-control" placeholder="City">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_state">State <span class="text-danger">*</span></label>
                <input type="text" id="shipping_state" name="shipping_state" class="form-control" placeholder="State">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_phone">Phone <span class="text-danger">*</span></label>
                <input type="text" id="shipping_phone" name="shipping_phone" class="form-control" placeholder="Phone">
            </div>
        </div>
        
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_country">Country <span class="text-danger">*</span></label>
                <select name="shipping_country" id="shipping_country" class="custom-select">
                    <option value="India">India</option>
                    <option value="United States">United States</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <!-- Add more countries here -->
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label>Additional Information</label>
                <textarea name="additional_information" class="form-control ht-50"></textarea>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('sameAsBilling');
        const shippingFields = document.getElementById('shippingAddressFields');

        // Set initial state based on checkbox value
        shippingFields.style.display = checkbox.checked ? 'none' : 'block';

        checkbox.addEventListener('change', function() {
            shippingFields.style.display = checkbox.checked ? 'none' : 'block';
        });
    });
</script>
