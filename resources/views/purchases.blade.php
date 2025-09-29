@extends('layouts.app')

@section('content')
    <h1 class="mb-4 fs-2">Purchase Management</h1>

    <ul class="nav nav-tabs" id="purchaseTab" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab">Purchase List</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#suppliers" type="button" role="tab">Suppliers</button></li>
    </ul>

    <div class="tab-content" id="purchaseTabContent">
        <div class="tab-pane fade show active" id="purchases" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPurchaseModal">Add New Purchase</button></div>
                <table class="table table-hover"><thead><tr><th>Ref #</th><th>Supplier</th><th>Date</th><th>Status</th><th>Total</th><th>Actions</th></tr></thead><tbody id="purchases-table-body"></tbody></table>
            </div>
        </div>
        <div class="tab-pane fade" id="suppliers" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add New Supplier</button></div>
                <table class="table table-hover"><thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Actions</th></tr></thead><tbody id="suppliers-table-body"></tbody></table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addSupplierModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Supplier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="add-supplier-form"><div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" id="supplier-name" required></div><div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" id="supplier-phone" required></div><div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="supplier-email"></div><div class="mb-3"><label class="form-label">Address</label><textarea class="form-control" id="supplier-address" rows="2"></textarea></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-supplier-btn">Save</button></div></div></div></div>
    <div class="modal fade" id="editSupplierModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Supplier</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="edit-supplier-form"><input type="hidden" id="edit-supplier-id"><div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" id="edit-supplier-name" required></div><div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" id="edit-supplier-phone" required></div><div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="edit-supplier-email"></div><div class="mb-3"><label class="form-label">Address</label><textarea class="form-control" id="edit-supplier-address" rows="2"></textarea></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-supplier-btn">Update</button></div></div></div></div>
    <div class="modal fade" id="addPurchaseModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add New Purchase</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="add-purchase-form"><div class="row"><div class="col-md-6 mb-3"><label class="form-label">Supplier</label><select class="form-select" id="purchase-supplier" required></select></div><div class="col-md-6 mb-3"><label class="form-label">Purchase Date</label><input type="date" class="form-control" id="purchase-date" required></div></div><div class="row"><div class="col-md-6 mb-3"><label class="form-label">Reference #</label><input type="text" class="form-control" id="purchase-reference" required></div><div class="col-md-6 mb-3"><label class="form-label">Status</label><select class="form-select" id="purchase-status"><option value="received">Received</option><option value="pending">Pending</option><option value="ordered">Ordered</option></select></div></div><hr><h6>Products</h6><div class="row align-items-end g-2"><div class="col-sm-5"><label class="form-label">Product</label><select class="form-select" id="purchase-product-select"></select></div><div class="col-sm-2"><label class="form-label">Qty</label><input type="number" class="form-control" id="purchase-product-qty" value="1" min="1"></div><div class="col-sm-3"><label class="form-label">Unit Price</label><input type="number" class="form-control" id="purchase-product-price" step="0.01"></div><div class="col-sm-2"><button type="button" class="btn btn-primary w-100" id="add-item-to-purchase-btn">Add</button></div></div><table class="table mt-3"><thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody id="purchase-items-table"></tbody></table><h4 class="text-end">Total: ₹<span id="purchase-total">0.00</span></h4></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-purchase-btn">Save Purchase</button></div></div></div></div>
    <div class="modal fade" id="editPurchaseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Purchase Status</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="edit-purchase-form">
                        <input type="hidden" id="edit-purchase-id">
                        <div class="mb-3">
                            <label for="edit-purchase-status" class="form-label">Status</label>
                            <select class="form-select" id="edit-purchase-status">
                                <option value="received">Received</option>
                                <option value="pending">Pending</option>
                                <option value="ordered">Ordered</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-purchase-btn">Update Status</button></div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchasesTableBody = document.getElementById('purchases-table-body');
    const suppliersTableBody = document.getElementById('suppliers-table-body');
    const modals = {
    supplier: new bootstrap.Modal(document.getElementById('addSupplierModal')),
    editSupplier: new bootstrap.Modal(document.getElementById('editSupplierModal')),
    purchase: new bootstrap.Modal(document.getElementById('addPurchaseModal')),
    editPurchase: new bootstrap.Modal(document.getElementById('editPurchaseModal')), // <-- Add this line
};
// Add this line below the modals object
const updatePurchaseBtn = document.getElementById('update-purchase-btn');


    let allSuppliers = [], allProducts = [], purchaseItems = [];

    const renderers = {
        purchase: (p) => {
    const supplierName = allSuppliers.find(s => s.id === p.supplier_id)?.name || 'N/A';
    const dataset = `data-id="${p.id}" data-status="${p.status}"`;
    return `<tr id="purchase-${p.id}"><td>${p.reference_no}</td><td>${supplierName}</td><td>${p.purchase_date}</td><td><span class="badge bg-primary">${p.status}</span></td><td>₹${parseFloat(p.total_amount || p.grand_total).toFixed(2)}</td><td><button class="btn btn-sm btn-info edit-btn" data-type="purchase" ${dataset}>Edit</button> <button class="btn btn-sm btn-danger delete-btn" data-type="purchase" data-id="${p.id}">Delete</button></td></tr>`;
},
        supplier: (s) => {
            const dataset = `data-id="${s.id}" data-name="${s.name}" data-phone="${s.phone}" data-email="${s.email || ''}" data-address="${s.address || ''}"`;
            return `<tr id="supplier-${s.id}"><td>${s.id}</td><td><strong>${s.name}</strong></td><td>${s.phone}</td><td><button class="btn btn-sm btn-info edit-btn" data-type="supplier" ${dataset}>Edit</button> <button class="btn btn-sm btn-danger delete-btn" data-type="supplier" data-id="${s.id}">Delete</button></td></tr>`;
        }
    };

    function fetchAllData() {
        Promise.all([
    fetch('/api/purchases'), // <-- CORRECT
    fetch('/api/suppliers'),
    fetch('/api/products')
])
            .then(responses => Promise.all(responses.map(res => res.json())))
            .then(([purchases, suppliers, products]) => {
                allSuppliers = suppliers; allProducts = products;
                purchasesTableBody.innerHTML = ''; suppliersTableBody.innerHTML = '';
                purchases.forEach(p => purchasesTableBody.innerHTML += renderers.purchase(p));
                suppliers.forEach(s => suppliersTableBody.innerHTML += renderers.supplier(s));
                
                const supplierOptions = '<option value="">Select...</option>' + suppliers.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
                const productOptions = '<option value="">Select...</option>' + products.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
                document.getElementById('purchase-supplier').innerHTML = supplierOptions;
                document.getElementById('purchase-product-select').innerHTML = productOptions;
            });
    }

    // --- Purchase Modal Logic ---
    document.getElementById('add-item-to-purchase-btn').addEventListener('click', function() {
        const productId = document.getElementById('purchase-product-select').value;
        const qty = document.getElementById('purchase-product-qty').value;
        const price = document.getElementById('purchase-product-price').value;
        if (!productId || !qty || !price) { alert('Please select a product, quantity, and price.'); return; }
        const product = allProducts.find(p => p.id == productId);
        purchaseItems.push({ product_id: productId, name: product.name, quantity: parseInt(qty), unit_price: parseFloat(price) });
        renderPurchaseItemsTable();
    });

    function renderPurchaseItemsTable() {
        const itemsTable = document.getElementById('purchase-items-table');
        let total = 0; itemsTable.innerHTML = '';
        purchaseItems.forEach(item => {
            const subtotal = item.quantity * item.unit_price;
            total += subtotal;
            itemsTable.innerHTML += `<tr><td>${item.name}</td><td>${item.quantity}</td><td>₹${item.unit_price.toFixed(2)}</td><td>₹${subtotal.toFixed(2)}</td></tr>`;
        });
        document.getElementById('purchase-total').innerText = total.toFixed(2);
    }

    // --- Event Listeners ---
    document.getElementById('save-supplier-btn').addEventListener('click', () => handleSave('supplier'));
    document.getElementById('save-purchase-btn').addEventListener('click', () => handleSave('purchase'));
    document.getElementById('update-purchase-btn').addEventListener('click', () => handleUpdate('purchase'));
    document.getElementById('purchaseTabContent').addEventListener('click', e => {
        const type = e.target.dataset.type;
        if (e.target.classList.contains('edit-btn')) handleEdit(type, e.target.dataset);
        if (e.target.classList.contains('delete-btn')) handleDelete(type, e.target.dataset.id);
    });

    // --- Handlers ---
    function handleSave(type) {
        let endpoint, body, formId, modal;
        if (type === 'supplier') { endpoint = '/api/suppliers'; body = { name: document.getElementById('supplier-name').value, phone: document.getElementById('supplier-phone').value, email: document.getElementById('supplier-email').value, address: document.getElementById('supplier-address').value }; formId = 'add-supplier-form'; modal = modals.supplier; }
        if (type === 'purchase') {
            endpoint = '/api/purchases';
            body = { supplier_id: document.getElementById('purchase-supplier').value, purchase_date: document.getElementById('purchase-date').value, reference_no: document.getElementById('purchase-reference').value, status: document.getElementById('purchase-status').value, total_amount: document.getElementById('purchase-total').innerText, products: purchaseItems.map(item => ({ product_id: item.product_id, quantity: item.quantity, unit_price: item.unit_price })) };
            if (purchaseItems.length === 0) { alert('Please add at least one product.'); return; }
            formId = 'add-purchase-form'; modal = modals.purchase;
        }
        fetch(endpoint, { method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify(body) })
            .then(res => res.json()).then(data => { if (!data.errors) { fetchAllData(); modal.hide(); document.getElementById(formId).reset(); if (type === 'purchase') { purchaseItems = []; renderPurchaseItemsTable(); } } else { alert('Error: ' + Object.values(data.errors).join('\\n')); }});
    }

    function handleEdit(type, dataset) {
        if (type === 'supplier') {
            document.getElementById('edit-supplier-id').value = dataset.id; document.getElementById('edit-supplier-name').value = dataset.name; document.getElementById('edit-supplier-phone').value = dataset.phone; document.getElementById('edit-supplier-email').value = dataset.email; document.getElementById('edit-supplier-address').value = dataset.address;
            modals.editSupplier.show();
        }
        // Add this new block
        if (type === 'purchase') {
        document.getElementById('edit-purchase-id').value = dataset.id;
        document.getElementById('edit-purchase-status').value = dataset.status;
        modals.editPurchase.show();
    }
    }

    function handleUpdate(type) {
    // This function now correctly handles both 'supplier' and 'purchase' types
    let id, endpoint, body, modal;

    if (type === 'supplier') {
        id = document.getElementById('edit-supplier-id').value;
        endpoint = `/api/suppliers/${id}`;
        body = {
            name: document.getElementById('edit-supplier-name').value,
            phone: document.getElementById('edit-supplier-phone').value,
            email: document.getElementById('edit-supplier-email').value,
            address: document.getElementById('edit-supplier-address').value
        };
        modal = modals.editSupplier;
    }

    if (type === 'purchase') {
        id = document.getElementById('edit-purchase-id').value;
        endpoint = `/api/purchases/${id}`;
        body = {
            status: document.getElementById('edit-purchase-status').value
        };
        modal = modals.editPurchase;
    }

    fetch(endpoint, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(res => res.json())
    .then(data => {
        if (!data.errors) {
            fetchAllData();
            modal.hide();
        } else {
            alert('Error: ' + Object.values(data.errors).join('\\n'));
        }
    });
}
        
    function handleDelete(type, id) {
        if (confirm(`Are you sure you want to delete this ${type}?`)) {
            const endpoint = `/api/${type}s/${id}`;
            fetch(endpoint, { method: 'DELETE' }).then(response => { if (response.ok) { fetchAllData(); } else { alert(`Failed to delete ${type}.`); }});
        }
    }

    fetchAllData();
});
</script>
@endsection