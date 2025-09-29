@extends('layouts.app')

@section('content')
    <h1 class="mb-4 fs-2">Application Settings</h1>

    <div class="card">
        <div class="card-body">
            <form id="settings-form">
                <h4 class="mb-3">General Settings</h4>
                <div class="mb-3">
                    <label for="store_name" class="form-label">Store Name</label>
                    <input type="text" class="form-control" id="store_name" name="store_name">
                </div>
                <div class="mb-3">
                    <label for="store_address" class="form-label">Store Address</label>
                    <textarea class="form-control" id="store_address" name="store_address" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="store_phone" class="form-label">Store Phone</label>
                    <input type="text" class="form-control" id="store_phone" name="store_phone">
                </div>
                <div class="mb-3">
                    <label for="currency_symbol" class="form-label">Currency Symbol</label>
                    <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" style="max-width: 100px;">
                </div>

                <hr class="my-4">

                <h4 class="mb-3">Tax & Charges</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="default_gst_rate" class="form-label">Default GST Rate (%)</label>
                        <input type="number" step="0.01" class="form-control" id="default_gst_rate" name="default_gst_rate">
                    </div>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="enable_service_charge" name="enable_service_charge" value="1">
                    <label class="form-check-label" for="enable_service_charge">Enable Service Charge</label>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="service_charge_type" class="form-label">Service Charge Type</label>
                        <select class="form-select" id="service_charge_type" name="service_charge_type">
                            <option value="fixed">Fixed (₹)</option>
                            <option value="percentage">Percentage (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="service_charge_value" class="form-label">Service Charge Value</label>
                        <input type="number" step="0.01" class="form-control" id="service_charge_value" name="service_charge_value">
                    </div>
                </div>
                
                <hr class="my-4">

                <h4 class="mb-3">Barcode Settings</h4>
                <div class="mb-3">
                    <label for="barcode_prefix" class="form-label">Barcode Prefix</label>
                    <input type="text" id="barcode_prefix" name="barcode_prefix" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="barcode_suffix" class="form-label">Barcode Suffix</label>
                    <input type="text" id="barcode_suffix" name="barcode_suffix" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary mt-3">Save All Settings</button>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const settingsForm = document.getElementById('settings-form');
    // Updated list to include all fields
    const fields = [
        'store_name', 'store_address', 'store_phone', 'currency_symbol', 
        'default_gst_rate', 'enable_service_charge', 'service_charge_type', 'service_charge_value',
        'barcode_prefix', 'barcode_suffix'
    ];

    // Fetch existing settings and populate the form
    fetch('/api/settings')
        .then(response => response.json())
        .then(settings => {
            fields.forEach(field => {
                const input = document.getElementById(field);
                if (input && settings[field] !== undefined) {
                    // Handle checkbox differently
                    if (input.type === 'checkbox') {
                        input.checked = settings[field] === '1';
                    } else {
                        input.value = settings[field];
                    }
                }
            });
        });

    // Handle form submission
    settingsForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(settingsForm);
        const settingsData = {};
        // Convert FormData to a plain object
        for (const [key, value] of formData.entries()) {
            settingsData[key] = value;
        }
        
        // Ensure the checkbox sends a '0' if it's unchecked
        if (!settingsData.enable_service_charge) {
            settingsData.enable_service_charge = '0';
        }

        fetch('/api/settings', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(settingsData)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Settings saved!');
        })
        .catch(error => {
            console.error('Error saving settings:', error);
            alert('An error occurred while saving settings.');
        });
    });
});
</script>
@endsection