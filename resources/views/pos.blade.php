@extends('layouts.app')

@section('content')
<h1 class="mb-4 fs-2">Point of Sale</h1>

<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Select Customer</h5>
                <select class="form-select" id="customer-select">
                    <option value="">Loading customers...</option>
                </select>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Add Service</h5>
                <div class="mb-3">
                    <label for="barcode-input" class="form-label">Scan or Type Barcode (and press Enter)</label>
                    <div class="input-group">
                        <input type="text" id="barcode-input" class="form-control" placeholder="Scan with external scanner...">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#scannerModal">Use Camera</button>
                    </div>
                </div>
                <div class="input-group">
                    <select class="form-select" id="service-select">
                        <option value="">Or select a service manually...</option>
                    </select>
                    <input type="number" class="form-control" id="service-quantity" value="1" min="1" style="max-width:80px;">
                    <button class="btn btn-primary" type="button" id="add-service-btn">Add to Order</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Order Summary</h5>
                <table class="table">
                    <thead><tr><th>Service</th><th>Qty</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody id="order-summary-table"></tbody>
                    <tfoot id="order-summary-footer" class="fw-bold">
                        </tfoot>
                </table>
                <div class="d-grid">
                    <button class="btn btn-success btn-lg" id="submit-order-btn">Submit Order</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scannerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Scan Barcode</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><div id="scanner-container" style="width:100%; height:400px; background:#000;"></div></div>
        </div>
    </div>
</div>

<style>
    #scanner-container video, #scanner-container canvas { width: 100% !important; height: 100% !important; object-fit: cover; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Variable Declarations ---
    let customers = [], services = [], orderItems = [], settings = {}, scannerStarted = false, isProcessingBarcode = false;

    // --- DOM Element References ---
    const customerSelect = document.getElementById('customer-select');
    const serviceSelect = document.getElementById('service-select');
    const quantityInput = document.getElementById('service-quantity');
    const addServiceBtn = document.getElementById('add-service-btn');
    const orderTableBody = document.getElementById('order-summary-table');
    const orderFooter = document.getElementById('order-summary-footer');
    const submitOrderBtn = document.getElementById('submit-order-btn');
    const scannerModalEl = document.getElementById('scannerModal');
    const scannerModal = new bootstrap.Modal(scannerModalEl);
    const barcodeInput = document.getElementById('barcode-input');

    // --- Main Data Fetching ---
    function fetchInitialData() {
        Promise.all([
            fetch('/api/customers'),
            fetch('/api/services'),
            fetch('/api/settings')
        ])
        .then(responses => Promise.all(responses.map(res => res.json())))
        .then(([fetchedCustomers, fetchedServices, fetchedSettings]) => {
            customers = fetchedCustomers;
            services = fetchedServices;
            settings = fetchedSettings;
            populateDropdowns();
        });
    }

    // --- Core Logic ---
    function addServiceToOrder(serviceId, quantity) {
        if (!serviceId || !quantity) return;
        const service = services.find(s => s.id === serviceId);
        if (!service) { alert('Service not found!'); return; }
        const existingItem = orderItems.find(item => item.service_id === service.id);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            orderItems.push({ service_id: service.id, name: service.name, quantity: quantity, unit_price: parseFloat(service.price) });
        }
        updateOrderSummary();
    }

    function findServiceByBarcode(code) {
        if (!code) return;
        fetch(`/api/services/barcode/${code}`)
            .then(res => { if (!res.ok) throw new Error('Service not found'); return res.json(); })
            .then(service => {
                addServiceToOrder(service.id, 1);
                barcodeInput.value = ''; barcodeInput.focus();
            })
            .catch(err => { alert('Service not found for scanned barcode.'); barcodeInput.select(); });
    }
    
    // --- Rendering Functions ---
    function populateDropdowns() {
        customerSelect.innerHTML = '<option value="">Select a customer</option>';
        customers.forEach(c => customerSelect.innerHTML += `<option value="${c.id}">${c.name} - ${c.phone}</option>`);
        serviceSelect.innerHTML = '<option value="">Or select a service manually...</option>';
        services.forEach(s => serviceSelect.innerHTML += `<option value="${s.id}">${s.name} - ₹${s.price}</option>`);
    }

    function updateOrderSummary() {
        orderTableBody.innerHTML = '';
        let subtotal = 0;
        orderItems.forEach(item => {
            const itemSubtotal = item.quantity * item.unit_price;
            subtotal += itemSubtotal;
            orderTableBody.innerHTML += `<tr><td>${item.name}</td><td>${item.quantity}</td><td class="text-end">₹${itemSubtotal.toFixed(2)}</td></tr>`;
        });

        let serviceCharge = 0;
        if (settings.enable_service_charge === '1') {
            const chargeValue = parseFloat(settings.service_charge_value) || 0;
            serviceCharge = settings.service_charge_type === 'percentage' ? subtotal * (chargeValue / 100) : chargeValue;
        }

        const taxRate = parseFloat(settings.default_gst_rate) || 0;
        const taxableAmount = subtotal + serviceCharge;
        const taxAmount = taxableAmount * (taxRate / 100);
        const grandTotal = subtotal + serviceCharge + taxAmount;

        orderFooter.innerHTML = `
            <tr><td colspan="2" class="text-end">Subtotal:</td><td class="text-end">₹${subtotal.toFixed(2)}</td></tr>
            ${serviceCharge > 0 ? `<tr><td colspan="2" class="text-end">Service Charge:</td><td class="text-end">₹${serviceCharge.toFixed(2)}</td></tr>` : ''}
            ${taxAmount > 0 ? `<tr><td colspan="2" class="text-end">GST @ ${taxRate}%:</td><td class="text-end">₹${taxAmount.toFixed(2)}</td></tr>` : ''}
            <tr class="fs-5"><td colspan="2" class="text-end">Grand Total:</td><td class="text-end">₹${grandTotal.toFixed(2)}</td></tr>
        `;
    }

    // --- EVENT LISTENERS ---
    addServiceBtn.addEventListener('click', () => addServiceToOrder(parseInt(serviceSelect.value), parseInt(quantityInput.value)));
    barcodeInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') { e.preventDefault(); findServiceByBarcode(barcodeInput.value); } });

    submitOrderBtn.addEventListener('click', function() {
        const customerId = parseInt(customerSelect.value);
        if(!customerId || orderItems.length === 0){ alert('Please select a customer and add at least one service.'); return; }
        const orderData = {
            customer_id: customerId, order_number: 'ORD-' + Date.now(),
            status: 'pending', payment_status: 'unpaid',
            order_date: new Date().toISOString().slice(0,10),
            services: orderItems.map(i => ({ service_id: i.service_id, quantity: i.quantity, unit_price: i.unit_price }))
        };
        fetch('/api/orders', { method: 'POST', headers: { 'Content-Type':'application/json','Accept':'application/json' }, body: JSON.stringify(orderData) })
            .then(res => res.json()).then(data => {
                if(data.errors) { alert('Error: ' + JSON.stringify(data.errors)); }
                else { alert('Order created! ID: ' + data.id); orderItems=[]; updateOrderSummary(); customerSelect.value=''; }
            });
    });

    scannerModalEl.addEventListener('shown.bs.modal', function () {
        isProcessingBarcode = false;
        if (typeof Quagga !== 'undefined' && !scannerStarted) {
            Quagga.init({ inputStream: { type: "LiveStream", target: document.querySelector('#scanner-container'), constraints: { facingMode: "environment" } }, decoder: { readers: ["code_128_reader", "ean_reader"] } }, (err) => {
                if (err) { console.error("Quagga init failed:", err); return; }
                Quagga.start(); scannerStarted = true;
                Quagga.onDetected((result) => {
                    if (isProcessingBarcode) return;
                    isProcessingBarcode = true;
                    Quagga.stop(); scannerStarted = false; scannerModal.hide();
                    findServiceByBarcode(result.codeResult.code);
                });
            });
        } else if (typeof Quagga !== 'undefined' && !scannerStarted) { Quagga.start(); scannerStarted = true; }
    });

    scannerModalEl.addEventListener('hidden.bs.modal', function () {
        if (scannerStarted && typeof Quagga !== 'undefined') { Quagga.stop(); scannerStarted = false; }
    });

    // --- Initial page load ---
    fetchInitialData();
    updateOrderSummary(); // Initial render of the empty summary
});
</script>
@endsection