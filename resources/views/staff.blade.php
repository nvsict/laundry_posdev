@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-4 fs-2">Staff Management</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add New Staff</button>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="staff-table-body">
                    </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addStaffModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add New Staff</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="add-staff-form">
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" id="staff-name" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="staff-email" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" id="staff-password" required></div>
                        <div class="mb-3"><label class="form-label">Role</label><select class="form-select" id="staff-role"><option value="staff">Staff</option><option value="admin">Admin</option></select></div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-staff-btn">Save Staff</button></div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editStaffModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Staff</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="edit-staff-form">
                        <input type="hidden" id="edit-staff-id">
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" id="edit-staff-name" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="edit-staff-email" required></div>
                        <div class="mb-3"><label class="form-label">New Password</label><input type="password" class="form-control" id="edit-staff-password"><small class="form-text text-muted">Leave blank to keep current password.</small></div>
                        <div class="mb-3"><label class="form-label">Role</label><select class="form-select" id="edit-staff-role"><option value="staff">Staff</option><option value="admin">Admin</option></select></div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-staff-btn">Update Staff</button></div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('staff-table-body');
    const modals = {
        add: new bootstrap.Modal(document.getElementById('addStaffModal')),
        edit: new bootstrap.Modal(document.getElementById('editStaffModal'))
    };

    function renderRow(user) {
        // CORRECTED: Get the role name from the 'roles' array relationship
        const roleName = user.roles.length > 0 ? user.roles[0].name : 'N/A';
        const dataset = `data-id="${user.id}" data-name="${user.name}" data-email="${user.email}" data-role="${roleName}"`;
        
        return `
            <tr id="staff-${user.id}">
                <td>${user.id}</td>
                <td><strong>${user.name}</strong></td>
                <td>${user.email}</td>
                <td><span class="badge bg-secondary">${roleName}</span></td>
                <td>
                    <button class="btn btn-sm btn-info edit-btn" ${dataset}>Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${user.id}">Delete</button>
                </td>
            </tr>
        `;
    }

    function fetchStaff() {
        fetch('/api/staff')
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(user => tableBody.innerHTML += renderRow(user));
            });
    }

    document.getElementById('save-staff-btn').addEventListener('click', function() {
        const staffData = {
            name: document.getElementById('staff-name').value,
            email: document.getElementById('staff-email').value,
            password: document.getElementById('staff-password').value,
            role: document.getElementById('staff-role').value,
        };

        fetch('/api/staff', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(staffData) })
            .then(res => res.json()).then(data => {
                if (data.errors) { alert('Error: ' + Object.values(data.errors).join('\\n')); } 
                else {
                    fetchStaff(); // Refresh the whole table to ensure data is correct
                    document.getElementById('add-staff-form').reset();
                    modals.add.hide();
                }
            });
    });
    
    tableBody.addEventListener('click', function(event) {
        const target = event.target;
        if (target.classList.contains('edit-btn')) {
            const ds = target.dataset;
            document.getElementById('edit-staff-id').value = ds.id;
            document.getElementById('edit-staff-name').value = ds.name;
            document.getElementById('edit-staff-email').value = ds.email;
            document.getElementById('edit-staff-role').value = ds.role;
            document.getElementById('edit-staff-password').value = '';
            modals.edit.show();
        }
        if (target.classList.contains('delete-btn')) {
            if (confirm('Are you sure you want to delete this staff member?')) {
                const staffId = target.dataset.id;
                fetch(`/api/staff/${staffId}`, { method: 'DELETE' })
                    .then(res => {
                        if (res.ok) {
                            document.getElementById(`staff-${staffId}`).remove();
                        } else {
                            res.json().then(data => alert(data.message || 'Failed to delete staff member.'));
                        }
                    });
            }
        }
    });

    document.getElementById('update-staff-btn').addEventListener('click', function() {
        const staffId = document.getElementById('edit-staff-id').value;
        const staffData = {
            name: document.getElementById('edit-staff-name').value,
            email: document.getElementById('edit-staff-email').value,
            role: document.getElementById('edit-staff-role').value,
            password: document.getElementById('edit-staff-password').value,
        };

        fetch(`/api/staff/${staffId}`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(staffData)})
            .then(res => res.json()).then(data => {
                if (data.errors) { alert('Error: ' + Object.values(data.errors).join('\\n')); } 
                else {
                    document.getElementById(`staff-${data.id}`).outerHTML = renderRow(data);
                    modals.edit.hide();
                }
            });
    });

    fetchStaff();
});
</script>
@endsection