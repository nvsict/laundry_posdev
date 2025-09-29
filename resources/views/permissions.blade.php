@extends('layouts.app')

@section('content')
    <h1 class="mb-4 fs-2">Role Permissions</h1>

    <ul class="nav nav-tabs" id="roleTabs" role="tablist">
        </ul>

    <div class="tab-content" id="roleTabsContent">
        </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleTabs = document.getElementById('roleTabs');
    const roleTabsContent = document.getElementById('roleTabsContent');

    fetch('/api/roles-permissions')
        .then(response => response.json())
        .then(data => {
            const allPermissions = data.permissions;
            const roles = data.roles;

            roles.forEach((role, index) => {
                // Create the tab button
                const tabButton = `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ${index === 0 ? 'active' : ''}" id="tab-${role.name}" data-bs-toggle="tab" data-bs-target="#pane-${role.name}" type="button" role="tab">${role.name.charAt(0).toUpperCase() + role.name.slice(1)}</button>
                    </li>`;
                roleTabs.innerHTML += tabButton;

                // Group permissions by category (e.g., 'order', 'customer')
                const permissionGroups = {};
                allPermissions.forEach(p => {
                    const group = p.name.split(' ')[1] || 'general';
                    if (!permissionGroups[group]) {
                        permissionGroups[group] = [];
                    }
                    permissionGroups[group].push(p);
                });

                // Build checkboxes for each permission
                let permissionsHtml = '';
                for (const groupName in permissionGroups) {
                    permissionsHtml += `<h5 class="mt-4">${groupName.charAt(0).toUpperCase() + groupName.slice(1)}</h5>`;
                    permissionGroups[groupName].forEach(permission => {
                        const hasPermission = role.permissions.some(p => p.id === permission.id);
                        permissionsHtml += `
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="${permission.name}" id="perm-${role.id}-${permission.id}" ${hasPermission ? 'checked' : ''}>
                                <label class="form-check-label" for="perm-${role.id}-${permission.id}">${permission.name}</label>
                            </div>
                        `;
                    });
                }

                // Create the tab pane content
                const tabPane = `
                    <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" id="pane-${role.name}" role="tabpanel">
                        <div class="card card-body border-top-0">
                            ${permissionsHtml}
                            <div class="mt-4">
                                <button class="btn btn-primary save-permissions-btn" data-role-id="${role.id}">Save Changes for ${role.name}</button>
                            </div>
                        </div>
                    </div>`;
                roleTabsContent.innerHTML += tabPane;
            });

            // Add event listeners to the save buttons
            document.querySelectorAll('.save-permissions-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const roleId = this.dataset.roleId;
                    const pane = document.getElementById(`pane-${roles.find(r=>r.id==roleId).name}`);
                    const checkedPermissions = [];
                    pane.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                        checkedPermissions.push(checkbox.value);
                    });

                    fetch(`/api/roles/${roleId}/permissions`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ permissions: checkedPermissions })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(err => alert('An error occurred.'));
                });
            });
        });
});
</script>
@endsection