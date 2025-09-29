@extends('layouts.app')

@section('content')
    <h1 class="mb-4 fs-2">Inventory Management</h1>

    <ul class="nav nav-tabs" id="inventoryTab" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Products</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">Categories</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="units-tab" data-bs-toggle="tab" data-bs-target="#units" type="button" role="tab">Units</button></li>
    </ul>

    <div class="tab-content" id="inventoryTabContent">
        <div class="tab-pane fade show active" id="products" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add New Product</button></div>
                <table class="table table-hover"><thead><tr><th>SKU</th><th>Name</th><th>Category</th><th>Stock</th><th>Unit</th><th>Actions</th></tr></thead><tbody id="products-table-body"></tbody></table>
            </div>
        </div>
        <div class="tab-pane fade" id="categories" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add New Category</button></div>
                <table class="table table-hover"><thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead><tbody id="categories-table-body"></tbody></table>
            </div>
        </div>
        <div class="tab-pane fade" id="units" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal">Add New Unit</button></div>
                <table class="table table-hover"><thead><tr><th>ID</th><th>Name</th><th>Short Name</th><th>Actions</th></tr></thead><tbody id="units-table-body"></tbody></table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add New Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="add-product-form"><div class="mb-3"><label class="form-label">Product Name</label><input type="text" class="form-control" id="product-name" required></div><div class="mb-3"><label class="form-label">SKU</label><input type="text" class="form-control" id="product-sku"></div><div class="mb-3"><label class="form-label">Initial Quantity</label><input type="number" class="form-control" id="product-quantity" value="0" required></div><div class="mb-3"><label class="form-label">Category</label><select class="form-select" id="product-category" required></select></div><div class="mb-3"><label class="form-label">Unit</label><select class="form-select" id="product-unit" required></select></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-product-btn">Save Product</button></div></div></div></div>
    <div class="modal fade" id="editProductModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Product</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="edit-product-form"><input type="hidden" id="edit-product-id"><div class="mb-3"><label class="form-label">Product Name</label><input type="text" class="form-control" id="edit-product-name" required></div><div class="mb-3"><label class="form-label">SKU</label><input type="text" class="form-control" id="edit-product-sku"></div><div class="mb-3"><label class="form-label">Quantity</label><input type="number" class="form-control" id="edit-product-quantity" required></div><div class="mb-3"><label class="form-label">Category</label><select class="form-select" id="edit-product-category" required></select></div><div class="mb-3"><label class="form-label">Unit</label><select class="form-select" id="edit-product-unit" required></select></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-product-btn">Update Product</button></div></div></div></div>
    <div class="modal fade" id="addCategoryModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add New Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="add-category-form"><div class="mb-3"><label class="form-label">Category Name</label><input type="text" class="form-control" id="category-name" required></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-category-btn">Save Category</button></div></div></div></div>
    <div class="modal fade" id="editCategoryModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="edit-category-form"><input type="hidden" id="edit-category-id"><div class="mb-3"><label class="form-label">Category Name</label><input type="text" class="form-control" id="edit-category-name" required></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-category-btn">Update Category</button></div></div></div></div>
    <div class="modal fade" id="addUnitModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add New Unit</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="add-unit-form"><div class="mb-3"><label class="form-label">Unit Name</label><input type="text" class="form-control" id="unit-name" required></div><div class="mb-3"><label class="form-label">Short Name</label><input type="text" class="form-control" id="unit-short-name" required></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-unit-btn">Save Unit</button></div></div></div></div>
    <div class="modal fade" id="editUnitModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Unit</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><form id="edit-unit-form"><input type="hidden" id="edit-unit-id"><div class="mb-3"><label class="form-label">Unit Name</label><input type="text" class="form-control" id="edit-unit-name" required></div><div class="mb-3"><label class="form-label">Short Name</label><input type="text" class="form-control" id="edit-unit-short-name" required></div></form></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-unit-btn">Update Unit</button></div></div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Get references to all HTML elements ---
    const productTableBody = document.getElementById('products-table-body');
    const categoryTableBody = document.getElementById('categories-table-body');
    const unitTableBody = document.getElementById('units-table-body');
    const modals = {
        product: new bootstrap.Modal(document.getElementById('addProductModal')),
        editProduct: new bootstrap.Modal(document.getElementById('editProductModal')),
        category: new bootstrap.Modal(document.getElementById('addCategoryModal')),
        editCategory: new bootstrap.Modal(document.getElementById('editCategoryModal')),
        unit: new bootstrap.Modal(document.getElementById('addUnitModal')),
        editUnit: new bootstrap.Modal(document.getElementById('editUnitModal')),
    };
    let allCategories = [], allUnits = [];

    // --- RENDER FUNCTIONS ---
    const renderers = {
        product: (p) => {
            const cat = allCategories.find(c => c.id === p.inventory_category_id)?.name || 'N/A';
            const unit = allUnits.find(u => u.id === p.unit_id)?.short_name || 'N/A';
            const dataset = `data-id="${p.id}" data-name="${p.name}" data-sku="${p.sku || ''}" data-quantity="${p.quantity}" data-unit_id="${p.unit_id}" data-inventory_category_id="${p.inventory_category_id}"`;
            return `<tr id="product-${p.id}"><td>${p.sku || 'N/A'}</td><td><strong>${p.name}</strong></td><td>${cat}</td><td>${p.quantity}</td><td>${unit}</td><td><button class="btn btn-sm btn-info edit-btn" data-type="product" ${dataset}>Edit</button> <button class="btn btn-sm btn-danger delete-btn" data-type="product" data-id="${p.id}">Delete</button></td></tr>`;
        },
        category: (c) => {
            const dataset = `data-id="${c.id}" data-name="${c.name}"`;
            return `<tr id="category-${c.id}"><td>${c.id}</td><td>${c.name}</td><td><button class="btn btn-sm btn-info edit-btn" data-type="category" ${dataset}>Edit</button> <button class="btn btn-sm btn-danger delete-btn" data-type="category" data-id="${c.id}">Delete</button></td></tr>`;
        },
        unit: (u) => {
            const dataset = `data-id="${u.id}" data-name="${u.name}" data-short_name="${u.short_name}"`;
            return `<tr id="unit-${u.id}"><td>${u.id}</td><td>${u.name}</td><td>${u.short_name}</td><td><button class="btn btn-sm btn-info edit-btn" data-type="unit" ${dataset}>Edit</button> <button class="btn btn-sm btn-danger delete-btn" data-type="unit" data-id="${u.id}">Delete</button></td></tr>`;
        }
    };

    // --- FETCH INITIAL DATA ---
    function fetchAllData() {
        Promise.all([fetch('/api/products'), fetch('/api/inventory-categories'), fetch('/api/units')])
            .then(responses => Promise.all(responses.map(res => res.json())))
            .then(([products, categories, units]) => {
                allCategories = categories; allUnits = units;
                productTableBody.innerHTML = ''; categoryTableBody.innerHTML = ''; unitTableBody.innerHTML = '';
                products.forEach(p => productTableBody.innerHTML += renderers.product(p));
                categories.forEach(c => categoryTableBody.innerHTML += renderers.category(c));
                units.forEach(u => unitTableBody.innerHTML += renderers.unit(u));
                const catOptions = '<option value="">Select...</option>' + categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
                const unitOptions = '<option value="">Select...</option>' + units.map(u => `<option value="${u.id}">${u.name}</option>`).join('');
                document.getElementById('product-category').innerHTML = catOptions; document.getElementById('edit-product-category').innerHTML = catOptions;
                document.getElementById('product-unit').innerHTML = unitOptions; document.getElementById('edit-product-unit').innerHTML = unitOptions;
            });
    }

    // --- EVENT LISTENERS & HANDLERS ---
    document.getElementById('save-product-btn').addEventListener('click', () => handleSave('product'));
    document.getElementById('save-category-btn').addEventListener('click', () => handleSave('category'));
    document.getElementById('save-unit-btn').addEventListener('click', () => handleSave('unit'));
    document.getElementById('update-product-btn').addEventListener('click', () => handleUpdate('product'));
    document.getElementById('update-category-btn').addEventListener('click', () => handleUpdate('category'));
    document.getElementById('update-unit-btn').addEventListener('click', () => handleUpdate('unit'));
    document.getElementById('inventoryTabContent').addEventListener('click', (e) => {
        const type = e.target.dataset.type;
        if (e.target.classList.contains('edit-btn')) handleEdit(type, e.target.dataset);
        if (e.target.classList.contains('delete-btn')) handleDelete(type, e.target.dataset.id);
    });

    function handleSave(type) {
        let endpoint, body, formId, modal;
        if (type === 'product') { endpoint = '/api/products'; body = { name: document.getElementById('product-name').value, sku: document.getElementById('product-sku').value, quantity: document.getElementById('product-quantity').value, unit_id: document.getElementById('product-unit').value, inventory_category_id: document.getElementById('product-category').value }; formId = 'add-product-form'; modal = modals.product; }
        if (type === 'category') { endpoint = '/api/inventory-categories'; body = { name: document.getElementById('category-name').value }; formId = 'add-category-form'; modal = modals.category; }
        if (type === 'unit') { endpoint = '/api/units'; body = { name: document.getElementById('unit-name').value, short_name: document.getElementById('unit-short-name').value }; formId = 'add-unit-form'; modal = modals.unit; }
        
        fetch(endpoint, { method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify(body) })
            .then(res => res.json()).then(data => { if (!data.errors) { fetchAllData(); modal.hide(); document.getElementById(formId).reset(); } else { alert('Error: ' + Object.values(data.errors).join('\\n')); }});
    }

    function handleEdit(type, dataset) {
        if (type === 'product') { document.getElementById('edit-product-id').value = dataset.id; document.getElementById('edit-product-name').value = dataset.name; document.getElementById('edit-product-sku').value = dataset.sku; document.getElementById('edit-product-quantity').value = dataset.quantity; document.getElementById('edit-product-category').value = dataset.inventory_category_id; document.getElementById('edit-product-unit').value = dataset.unit_id; modals.editProduct.show(); }
        if (type === 'category') { document.getElementById('edit-category-id').value = dataset.id; document.getElementById('edit-category-name').value = dataset.name; modals.editCategory.show(); }
        if (type === 'unit') { document.getElementById('edit-unit-id').value = dataset.id; document.getElementById('edit-unit-name').value = dataset.name; document.getElementById('edit-unit-short-name').value = dataset.short_name; modals.editUnit.show(); }
    }

    function handleUpdate(type) {
        let id, endpoint, body, modal;
        if (type === 'product') { id = document.getElementById('edit-product-id').value; endpoint = `/api/products/${id}`; body = { name: document.getElementById('edit-product-name').value, sku: document.getElementById('edit-product-sku').value, quantity: document.getElementById('edit-product-quantity').value, unit_id: document.getElementById('edit-product-unit').value, inventory_category_id: document.getElementById('edit-product-category').value }; modal = modals.editProduct; }
        if (type === 'category') { id = document.getElementById('edit-category-id').value; endpoint = `/api/inventory-categories/${id}`; body = { name: document.getElementById('edit-category-name').value }; modal = modals.editCategory; }
        if (type === 'unit') { id = document.getElementById('edit-unit-id').value; endpoint = `/api/units/${id}`; body = { name: document.getElementById('edit-unit-name').value, short_name: document.getElementById('edit-unit-short-name').value }; modal = modals.editUnit; }
        
        fetch(endpoint, { method: 'PUT', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify(body) })
            .then(res => res.json()).then(data => { if (!data.errors) { fetchAllData(); modal.hide(); } else { alert('Error: ' + Object.values(data.errors).join('\\n')); }});
    }

    function handleDelete(type, id) {
        if (confirm(`Are you sure you want to delete this ${type}?`)) {
            const endpoint = `/api/${type === 'category' ? 'inventory-categories' : type + 's'}/${id}`;
            fetch(endpoint, { method: 'DELETE' })
                .then(response => { if (response.ok) { fetchAllData(); } else { alert(`Failed to delete ${type}.`); }});
        }
    }

    fetchAllData();
});
</script>
@endsection