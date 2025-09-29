@extends('layouts.app')

@section('content')
    <h1 class="mb-4 fs-2">Service Management</h1>

    <ul class="nav nav-tabs" id="serviceTab" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">Services</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">Categories</button></li>
    </ul>

    <div class="tab-content" id="serviceTabContent">
        <div class="tab-pane fade show active" id="services" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">Add New Service</button></div>
                <table class="table table-hover"><thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Barcode</th><th>Actions</th></tr></thead><tbody id="services-table-body"></tbody></table>
            </div>
        </div>
        <div class="tab-pane fade" id="categories" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add New Category</button></div>
                <table class="table table-hover"><thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead><tbody id="categories-table-body"></tbody></table>
            </div>
        </div>
    </div>

   {{-- Modals Section --}}

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-service-form">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" step="0.01" class="form-control" id="price" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price Type</label>
                        <select class="form-select" id="price_type">
                            <option value="per_item">Per Item</option>
                            <option value="per_kg">Per Kg</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Category</label>
                        <select class="form-select" id="service_type_id" required></select>
                    </div>
                    <div class="mb-3">
    <label class="form-label">Barcode</label>
    <div class="input-group">
        <input type="text" class="form-control" id="barcode">
        <button type="button" class="btn btn-outline-secondary" id="generate-barcode">Generate</button>
    </div>
</div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-service-btn">Save Service</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-service-form">
                    <input type="hidden" id="edit-service-id">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="edit-name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" step="0.01" class="form-control" id="edit-price" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price Type</label>
                        <select class="form-select" id="edit-price_type">
                            <option value="per_item">Per Item</option>
                            <option value="per_kg">Per Kg</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Service Category</label>
                        <select class="form-select" id="edit-service_type_id" required></select>
                    </div>
                    <div class="mb-3">
    <label class="form-label">Barcode</label>
    <div class="input-group">
        <input type="text" class="form-control" id="edit-barcode">
        <button type="button" class="btn btn-outline-secondary" id="generate-edit-barcode">Generate</button>
    </div>
</div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-service-btn">Update Service</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-category-form">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category-name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-category-btn">Save Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-category-form">
                    <input type="hidden" id="edit-category-id">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="edit-category-name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-category-btn">Update Category</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Get references ---
    const servicesTableBody = document.getElementById('services-table-body');
    const categoriesTableBody = document.getElementById('categories-table-body');
    const modals = {
        service: new bootstrap.Modal(document.getElementById('addServiceModal')),
        editService: new bootstrap.Modal(document.getElementById('editServiceModal')),
        category: new bootstrap.Modal(document.getElementById('addCategoryModal')),
        editCategory: new bootstrap.Modal(document.getElementById('editCategoryModal')),
    };
    let serviceTypes = [];

    // --- RENDER FUNCTIONS ---
    function renderServiceRow(service) {
        const categoryName = serviceTypes.find(type => type.id == service.service_type_id)?.name || 'N/A';
        const dataset = `data-id="${service.id}" data-name="${service.name}" data-price="${service.price}" data-price_type="${service.price_type}" data-service_type_id="${service.service_type_id}" data-barcode="${service.barcode || ''}"`;
        return `<tr id="service-${service.id}">
                    <td>${service.id}</td>
                    <td><strong>${service.name}</strong></td>
                    <td>₹${parseFloat(service.price).toFixed(2)}</td>
                    <td>${categoryName}</td>
                    <td>${service.barcode || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-info edit-btn" data-type="service" ${dataset}>Edit</button> 
                        <button class="btn btn-sm btn-danger delete-btn" data-type="service" data-id="${service.id}">Delete</button>
                    </td>
                </tr>`;
    }
    function renderCategoryRow(category) {
        const dataset = `data-id="${category.id}" data-name="${category.name}"`;
        return `<tr id="category-${category.id}">
                    <td>${category.id}</td>
                    <td>${category.name}</td>
                    <td>
                        <button class="btn btn-sm btn-info edit-btn" data-type="category" ${dataset}>Edit</button> 
                        <button class="btn btn-sm btn-danger delete-btn" data-type="category" data-id="${category.id}">Delete</button>
                    </td>
                </tr>`;
    }

    // --- FETCH FUNCTIONS ---
    function fetchServices() {
        fetch('/api/services')
            .then(res => res.json())
            .then(services => {
                servicesTableBody.innerHTML = '';
                services.forEach(service => {
                    servicesTableBody.innerHTML += renderServiceRow(service);
                });
            })
            .catch(error => { console.error('Error fetching services:', error); alert('Failed to load services.'); });
    }

    function fetchCategories() {
        fetch('/api/service-types')
            .then(res => res.json())
            .then(types => {
                serviceTypes = types;
                categoriesTableBody.innerHTML = '';
                types.forEach(category => {
                    categoriesTableBody.innerHTML += renderCategoryRow(category);
                });

                // update category dropdowns
                const typeOptions = '<option value="">Select a category</option>' +
                    types.map(type => `<option value="${type.id}">${type.name}</option>`).join('');
                document.getElementById('service_type_id').innerHTML = typeOptions;
                document.getElementById('edit-service_type_id').innerHTML = typeOptions;

                // re-render services to show category names properly
                fetchServices();
            })
            .catch(error => { console.error('Error fetching categories:', error); alert('Failed to load categories.'); });
    }

    function fetchAllData() { fetchCategories(); }

    // --- BARCODE GENERATION FUNCTIONS ---
    async function generateServiceBarcode() {
        const nameInput = document.getElementById('name');
        const categoryId = document.getElementById('service_type_id').value;
        const priceType = document.getElementById('price_type').value;

        if (!nameInput.value.trim() || !categoryId) { alert('Please enter Service Name and select Category first.'); return; }

        const name = nameInput.value.trim().toUpperCase().substring(0,2);
        const category = serviceTypes.find(c => c.id == categoryId)?.name.toUpperCase().substring(0,2) || 'NA';
        const typeCode = priceType === 'per_item' ? 'P' : 'K';

        try {
            const res = await fetch('/api/services');
            const services = await res.json();
            let maxNum = 0;
            services.forEach(service => {
                const match = service.barcode?.match(/-(\d+)$/);
                if (match) { const num = parseInt(match[1]); if (!isNaN(num) && num > maxNum) maxNum = num; }
            });
            const nextNum = maxNum + 1 || 1;
            document.getElementById('barcode').value = `${name}-${category}-${typeCode}-${nextNum}`;
        } catch (error) { console.error('Error generating barcode:', error); }
    }

    async function generateEditServiceBarcode() {
        const nameInput = document.getElementById('edit-name');
        const categoryId = document.getElementById('edit-service_type_id').value;
        const priceType = document.getElementById('edit-price_type').value;

        if (!nameInput.value.trim() || !categoryId) { alert('Please enter Service Name and select Category first.'); return; }

        const name = nameInput.value.trim().toUpperCase().substring(0,2);
        const category = serviceTypes.find(c => c.id == categoryId)?.name.toUpperCase().substring(0,2) || 'NA';
        const typeCode = priceType === 'per_item' ? 'P' : 'K';

        try {
            const res = await fetch('/api/services');
            const services = await res.json();
            const currentId = parseInt(document.getElementById('edit-service-id').value);
            let maxNum = 0;
            services.forEach(service => {
                if (service.id == currentId) return;
                const match = service.barcode?.match(/-(\d+)$/);
                if (match) { const num = parseInt(match[1]); if (!isNaN(num) && num > maxNum) maxNum = num; }
            });
            const nextNum = maxNum + 1 || 1;
            document.getElementById('edit-barcode').value = `${name}-${category}-${typeCode}-${nextNum}`;
        } catch (error) { console.error('Error generating barcode:', error); }
    }

    // --- EVENT LISTENERS ---
    document.getElementById('save-service-btn').addEventListener('click', () => handleSave('service'));
    document.getElementById('save-category-btn').addEventListener('click', () => handleSave('category'));
    document.getElementById('update-service-btn').addEventListener('click', () => handleUpdate('service'));
    document.getElementById('update-category-btn').addEventListener('click', () => handleUpdate('category'));
    document.getElementById('serviceTabContent').addEventListener('click', (e) => {
        const type = e.target.dataset.type;
        if (e.target.classList.contains('edit-btn')) handleEdit(type, e.target.dataset);
        if (e.target.classList.contains('delete-btn')) handleDelete(type, e.target.dataset.id);
    });

    document.getElementById('generate-barcode').addEventListener('click', generateServiceBarcode);
    document.getElementById('generate-edit-barcode').addEventListener('click', generateEditServiceBarcode);

    function handleSave(type) {
        let endpoint, body, formId, modal;
        if (type === 'service') { 
            endpoint = '/api/services'; 
            body = { 
                name: document.getElementById('name').value, 
                price: document.getElementById('price').value, 
                price_type: document.getElementById('price_type').value, 
                service_type_id: document.getElementById('service_type_id').value, 
                barcode: document.getElementById('barcode').value 
            }; 
            formId = 'add-service-form'; modal = modals.service; 
        }
        if (type === 'category') { 
            endpoint = '/api/service-types'; 
            body = { name: document.getElementById('category-name').value }; 
            formId = 'add-category-form'; modal = modals.category; 
        }
        fetch(endpoint, { method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify(body) })
            .then(res => res.json())
            .then(data => { 
                if (!data.errors) { fetchAllData(); modal.hide(); document.getElementById(formId).reset(); } 
                else { alert('Error: ' + Object.values(data.errors).join('\n')); }
            });
    }

    function handleEdit(type, dataset) {
        if (type === 'service') { 
            document.getElementById('edit-service-id').value = dataset.id;
            document.getElementById('edit-name').value = dataset.name;
            document.getElementById('edit-price').value = dataset.price;
            document.getElementById('edit-price_type').value = dataset.price_type;
            document.getElementById('edit-service_type_id').value = dataset.service_type_id;
            document.getElementById('edit-barcode').value = dataset.barcode;
            modals.editService.show();
        }
        if (type === 'category') { 
            document.getElementById('edit-category-id').value = dataset.id;
            document.getElementById('edit-category-name').value = dataset.name;
            modals.editCategory.show();
        }
    }

    function handleUpdate(type) {
        let id, endpoint, body, modal;
        if (type === 'service') { 
            id = document.getElementById('edit-service-id').value; 
            endpoint = `/api/services/${id}`; 
            body = { 
                name: document.getElementById('edit-name').value, 
                price: document.getElementById('edit-price').value, 
                price_type: document.getElementById('edit-price_type').value, 
                service_type_id: document.getElementById('edit-service_type_id').value, 
                barcode: document.getElementById('edit-barcode').value 
            }; 
            modal = modals.editService; 
        }
        if (type === 'category') { 
            id = document.getElementById('edit-category-id').value; 
            endpoint = `/api/service-types/${id}`; 
            body = { name: document.getElementById('edit-category-name').value }; 
            modal = modals.editCategory; 
        }
        fetch(endpoint, { method: 'PUT', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify(body) })
            .then(res => res.json())
            .then(data => { if (!data.errors) { fetchAllData(); modal.hide(); } 
            else { alert('Error: ' + Object.values(data.errors).join('\n')); }});
    }

    function handleDelete(type, id) {
        if (confirm(`Are you sure you want to delete this ${type}?`)) {
            const endpoint = `/api/${type === 'category' ? 'service-types' : 'services'}/${id}`;
            fetch(endpoint, { method: 'DELETE' })
                .then(response => { if (response.ok) fetchAllData(); else alert(`Failed to delete ${type}.`); });
        }
    }

    // --- Initial Load ---
    fetchAllData();
});


</script>
@endsection
