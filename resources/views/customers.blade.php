@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-4 fs-2">Customers</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add New Customer</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Actions</th></tr></thead>
                <tbody id="customers-table-body"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add New Customer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><form id="add-customer-form"><div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" id="name" required></div><div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" id="phone" required></div><div class="mb-3"><label class="form-label">Email (Optional)</label><input type="email" class="form-control" id="email"></div><div class="mb-3"><label class="form-label">Address (Optional)</label><textarea class="form-control" id="address" rows="3"></textarea></div></form></div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-customer-btn">Save Customer</button></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Customer</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="edit-customer-form">
                        <input type="hidden" id="edit-customer-id">
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" id="edit-name" required></div>
                        <div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" id="edit-phone" required></div>
                        <div class="mb-3"><label class="form-label">Email (Optional)</label><input type="email" class="form-control" id="edit-email"></div>
                        <div class="mb-3"><label class="form-label">Address (Optional)</label><textarea class="form-control" id="edit-address" rows="3"></textarea></div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-customer-btn">Update Customer</button></div>
            </div>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('customers-table-body');
    const saveCustomerBtn = document.getElementById('save-customer-btn');
    const updateCustomerBtn = document.getElementById('update-customer-btn');
    const addCustomerForm = document.getElementById('add-customer-form');
    const addCustomerModal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
    const editCustomerModal = new bootstrap.Modal(document.getElementById('editCustomerModal'));

    function renderRow(customer) {
        return `
            <tr id="customer-${customer.id}">
                <td>${customer.id}</td>
                <td><strong>${customer.name}</strong></td>
                <td>${customer.phone}</td>
                <td>${customer.email || 'N/A'}</td>
                <td>
                    <button class="btn btn-sm btn-info edit-btn" data-id="${customer.id}" data-name="${customer.name}" data-phone="${customer.phone}" data-email="${customer.email || ''}" data-address="${customer.address || ''}">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${customer.id}">Delete</button>
                </td>
            </tr>
        `;
    }

    function fetchCustomers() {
        fetch('/api/customers').then(response => response.json()).then(data => {
            tableBody.innerHTML = '';
            data.forEach(customer => tableBody.innerHTML += renderRow(customer));
        });
    }

    saveCustomerBtn.addEventListener('click', function() {
        const customerData = { name: document.getElementById('name').value, phone: document.getElementById('phone').value, email: document.getElementById('email').value, address: document.getElementById('address').value, };
        fetch('/api/customers', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(customerData) })
            .then(res => res.json()).then(data => {
                if (data.errors) { alert('Error: ' + Object.values(data.errors).join('\\n')); } 
                else { tableBody.insertAdjacentHTML('beforeend', renderRow(data)); addCustomerForm.reset(); addCustomerModal.hide(); }
            });
    });

    // --- NEW: Handle Edit and Delete button clicks ---
    tableBody.addEventListener('click', function(event) {
        const target = event.target;

        // --- Handle Edit Click ---
        if (target.classList.contains('edit-btn')) {
            document.getElementById('edit-customer-id').value = target.dataset.id;
            document.getElementById('edit-name').value = target.dataset.name;
            document.getElementById('edit-phone').value = target.dataset.phone;
            document.getElementById('edit-email').value = target.dataset.email;
            document.getElementById('edit-address').value = target.dataset.address;
            editCustomerModal.show();
        }

        // --- Handle Delete Click ---
        if (target.classList.contains('delete-btn')) {
            if (confirm('Are you sure you want to delete this customer?')) {
                const customerId = target.dataset.id;
                fetch(`/api/customers/${customerId}`, { method: 'DELETE' })
                    .then(response => {
                        if (response.ok) {
                            document.getElementById(`customer-${customerId}`).remove();
                            alert('Customer deleted successfully.');
                        } else {
                            alert('Failed to delete customer.');
                        }
                    });
            }
        }
    });

    // --- NEW: Handle the "Update" button click in the edit modal ---
    updateCustomerBtn.addEventListener('click', function() {
        const customerId = document.getElementById('edit-customer-id').value;
        const customerData = {
            name: document.getElementById('edit-name').value,
            phone: document.getElementById('edit-phone').value,
            email: document.getElementById('edit-email').value,
            address: document.getElementById('edit-address').value,
        };

        fetch(`/api/customers/${customerId}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(customerData)})
            .then(res => res.json()).then(data => {
                if (data.errors) { alert('Error: ' + Object.values(data.errors).join('\\n')); } 
                else {
                    const originalRow = document.getElementById(`customer-${data.id}`);
                    originalRow.outerHTML = renderRow(data); // Replace the old row with the updated one
                    editCustomerModal.hide();
                }
            });
    });

    fetchCustomers();
});
</script>
@endsection