<div class="row mb-4">
    <div class="col-12">
        <input id="sameAsBilling" class="checkbox-custom" name="same_as_billing" type="checkbox"
            onchange="toggleShippingAddress()">
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
                <input type="text" id="shipping_company" name="shipping_company" class="form-control"
                    placeholder="Company">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_address_1">Address Line 1 <span class="text-danger">*</span></label>
                <input type="text" id="shipping_address_1" name="shipping_address_1" class="form-control"
                    placeholder="Address Line 1">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_address_2">Address Line 2</label>
                <input type="text" id="shipping_address_2" name="shipping_address_2" class="form-control"
                    placeholder="Address Line 2">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label for="shipping_zip">Zip Code <span class="text-danger">*</span></label>
                <input type="text" id="shipping_zip" name="shipping_zip" class="form-control" placeholder="Zip Code">
            </div>
        </div>

        <div id="shipping-delivery-message" class="col-12">
            <p id="shipping-delivery-status" class="text-info"></p>
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
                <input type="text" id="shipping_state" name="shipping_state" class="form-control"
                    placeholder="State">
            </div>
        </div>
        {{-- want to add shipping country code according to me --}}
        <div class="form-group col-md-6">
            <label for="shipping_country_code">Country Code *</label>
            <input type="text" class="form-control" name="shipping_country_code" id="shipping_country_code"
                value="{{ old('shipping_country_code', $billingAddress->shipping_country_code ?? '') }}" required />
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="shipping_phone">Phone <span class="text-danger">*</span></label>
                <input type="text" id="shipping_phone" name="shipping_phone" class="form-control"
                    placeholder="Phone">
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

    document.addEventListener("DOMContentLoaded", function() {
        const zipInput = document.getElementById('shipping_zip');
        const cityInput = document.getElementById('shipping_city');
        const stateInput = document.getElementById('shipping_state');
        const deliveryStatus = document.getElementById('shipping-delivery-status');

        if (!zipInput || !deliveryStatus) {
            console.error("Required elements not found.");
            return;
        }

        zipInput.addEventListener('change', async function() {
            const zip = this.value.trim();

            if (!zip) {
                displayMessage("Please enter a valid zip code.");
                return;
            }

            try {
                const locationData = await fetchZipData(zip);
                if (locationData) {
                    cityInput.value = locationData.city;
                    stateInput.value = locationData.state;

                    checkDeliveryAvailability(locationData.city, locationData.state);
                } else {
                    displayMessage("Invalid pincode or no delivery available.");
                }
            } catch (error) {
                console.error("Error fetching zip data:", error);
                displayMessage("Error checking delivery availability.");
            }
        });

        async function fetchZipData(zip) {
            try {
                const response = await fetch(`https://api.postalpincode.in/pincode/${zip}`);
                const data = await response.json();
                if (data[0]?.Status === 'Success') {
                    return {
                        city: data[0].PostOffice[0].Division,
                        state: data[0].PostOffice[0].State,
                    };
                }
                return null;
            } catch (error) {
                throw new Error("Failed to fetch location data.");
            }
        }

        function checkDeliveryAvailability(city, state) {
            const routeTemplate =
                `{{ route('check-delivery-shipping', ['shipping_city' => '__CITY__', 'shipping_state' => '__STATE__']) }}`;
            const finalUrl = routeTemplate
                .replace('__CITY__', encodeURIComponent(city))
                .replace('__STATE__', encodeURIComponent(state));

            fetch(finalUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.delivery_available) {
                        displayMessage("Delivery is available to your location.");
                    } else {
                        displayMessage("Sorry, we do not deliver to your location.");
                    }
                })
                .catch(error => {
                    console.error("Error checking delivery availability:", error);
                    displayMessage("Error checking delivery availability.");
                });
        }

        function displayMessage(message) {
            deliveryStatus.textContent = message;
        }
    });
</script>
