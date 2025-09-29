@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Expense Management</h1>

    <ul class="nav nav-tabs" id="expenseTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses" type="button" role="tab">Expense List</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">Categories</button>
        </li>
    </ul>

    <div class="tab-content" id="expenseTabContent">
        <div class="tab-pane fade show active" id="expenses" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">Add New Expense</button>
                </div>
                <table class="table table-hover">
                    <thead><tr><th>Date</th><th>Category</th><th>Amount</th><th>Description</th><th>Actions</th></tr></thead>
                    <tbody id="expenses-table-body"></tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="categories" role="tabpanel">
            <div class="card card-body border-top-0">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add New Category</button>
                </div>
                <table class="table table-hover">
                    <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
                    <tbody id="categories-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addExpenseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add New Expense</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="add-expense-form">
                        <div class="mb-3"><label class="form-label">Category</label><select class="form-select" id="expense-category" required></select></div>
                        <div class="mb-3"><label class="form-label">Amount (₹)</label><input type="number" step="0.01" class="form-control" id="expense-amount" required></div>
                        <div class="mb-3"><label class="form-label">Expense Date</label><input type="date" class="form-control" id="expense-date" required></div>
                        <div class="mb-3"><label class="form-label">Description (Optional)</label><textarea class="form-control" id="expense-description" rows="2"></textarea></div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-expense-btn">Save Expense</button></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editExpenseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Expense</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="edit-expense-form">
                        <input type="hidden" id="edit-expense-id">
                        <div class="mb-3"><label class="form-label">Category</label><select class="form-select" id="edit-expense-category" required></select></div>
                        <div class="mb-3"><label class="form-label">Amount (₹)</label><input type="number" step="0.01" class="form-control" id="edit-expense-amount" required></div>
                        <div class="mb-3"><label class="form-label">Expense Date</label><input type="date" class="form-control" id="edit-expense-date" required></div>
                        <div class="mb-3"><label class="form-label">Description (Optional)</label><textarea class="form-control" id="edit-expense-description" rows="2"></textarea></div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-expense-btn">Update Expense</button></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Add New Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><form id="add-category-form"><div class="mb-3"><label class="form-label">Category Name</label><input type="text" class="form-control" id="category-name" required></div></form></div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="save-category-btn">Save Category</button></div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Edit Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body"><form id="edit-category-form"><input type="hidden" id="edit-category-id"><div class="mb-3"><label class="form-label">Category Name</label><input type="text" class="form-control" id="edit-category-name" required></div></form></div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-primary" id="update-category-btn">Update Category</button></div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Get references ---
    const expensesTableBody = document.getElementById('expenses-table-body');
    const categoriesTableBody = document.getElementById('categories-table-body');
    const modals = {
        expense: new bootstrap.Modal(document.getElementById('addExpenseModal')),
        editExpense: new bootstrap.Modal(document.getElementById('editExpenseModal')),
        category: new bootstrap.Modal(document.getElementById('addCategoryModal')),
        editCategory: new bootstrap.Modal(document.getElementById('editCategoryModal')),
    };
    let allCategories = [];

    // --- RENDER FUNCTIONS (NOW COMPLETE) ---
    function renderExpenseRow(expense) {
        const categoryName = allCategories.find(c => c.id === expense.expense_category_id)?.name || 'N/A';
        const dataset = `data-id="${expense.id}" data-expense_category_id="${expense.expense_category_id}" data-amount="${expense.amount}" data-expense_date="${expense.expense_date}" data-description="${expense.description || ''}"`;
        return `
            <tr id="expense-${expense.id}">
                <td>${expense.expense_date}</td>
                <td>${categoryName}</td>
                <td>₹${parseFloat(expense.amount).toFixed(2)}</td>
                <td>${expense.description || ''}</td>
                <td>
                    <button class="btn btn-sm btn-info edit-btn" data-type="expense" ${dataset}>Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-type="expense" data-id="${expense.id}">Delete</button>
                </td>
            </tr>`;
    }
    function renderCategoryRow(category) {
        const dataset = `data-id="${category.id}" data-name="${category.name}"`;
        return `
            <tr id="category-${category.id}">
                <td>${category.id}</td>
                <td>${category.name}</td>
                <td>
                    <button class="btn btn-sm btn-info edit-btn" data-type="category" ${dataset}>Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-type="category" data-id="${category.id}">Delete</button>
                </td>
            </tr>`;
    }

    // --- FETCH INITIAL DATA ---
    function fetchAllData() {
        Promise.all([fetch('/api/expenses'), fetch('/api/expense-categories')])
            .then(responses => Promise.all(responses.map(res => res.json())))
            .then(([expenses, categories]) => {
                allCategories = categories;
                expensesTableBody.innerHTML = '';
                categoriesTableBody.innerHTML = '';
                expenses.forEach(expense => expensesTableBody.innerHTML += renderExpenseRow(expense));
                categories.forEach(category => categoriesTableBody.innerHTML += renderCategoryRow(category));

                const catOptions = '<option value="">Select a category</option>' + categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
                document.getElementById('expense-category').innerHTML = catOptions;
                document.getElementById('edit-expense-category').innerHTML = catOptions;
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // --- EVENT LISTENERS & HANDLERS ---
    document.getElementById('save-expense-btn').addEventListener('click', () => handleSave('expense'));
    document.getElementById('save-category-btn').addEventListener('click', () => handleSave('category'));
    document.getElementById('update-expense-btn').addEventListener('click', () => handleUpdate('expense'));
    document.getElementById('update-category-btn').addEventListener('click', () => handleUpdate('category'));
    document.getElementById('expenseTabContent').addEventListener('click', (e) => {
        const type = e.target.dataset.type;
        if (e.target.classList.contains('edit-btn')) handleEdit(type, e.target.dataset);
        if (e.target.classList.contains('delete-btn')) handleDelete(type, e.target.dataset.id);
    });

    function handleSave(type) {
        let endpoint, body, formId, modal;
        if (type === 'expense') { endpoint = '/api/expenses'; body = { expense_category_id: document.getElementById('expense-category').value, amount: document.getElementById('expense-amount').value, expense_date: document.getElementById('expense-date').value, description: document.getElementById('expense-description').value }; formId = 'add-expense-form'; modal = modals.expense; }
        if (type === 'category') { endpoint = '/api/expense-categories'; body = { name: document.getElementById('category-name').value }; formId = 'add-category-form'; modal = modals.category; }
        fetch(endpoint, { method: 'POST', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify(body) })
            .then(res => res.json()).then(data => { if (!data.errors) { fetchAllData(); modal.hide(); document.getElementById(formId).reset(); } else { alert('Error: ' + Object.values(data.errors).join('\\n')); }});
    }

    function handleEdit(type, dataset) {
        if (type === 'expense') { 
            document.getElementById('edit-expense-id').value = dataset.id;
            document.getElementById('edit-expense-category').value = dataset.expense_category_id;
            document.getElementById('edit-expense-amount').value = dataset.amount;
            document.getElementById('edit-expense-date').value = dataset.expense_date;
            document.getElementById('edit-expense-description').value = dataset.description;
            modals.editExpense.show(); 
        }
        if (type === 'category') { 
            document.getElementById('edit-category-id').value = dataset.id;
            document.getElementById('edit-category-name').value = dataset.name;
            modals.editCategory.show();
        }
    }

    function handleUpdate(type) {
        let id, endpoint, body, modal;
        if (type === 'expense') { id = document.getElementById('edit-expense-id').value; endpoint = `/api/expenses/${id}`; body = { expense_category_id: document.getElementById('edit-expense-category').value, amount: document.getElementById('edit-expense-amount').value, expense_date: document.getElementById('edit-expense-date').value, description: document.getElementById('edit-expense-description').value }; modal = modals.editExpense; }
        if (type === 'category') { id = document.getElementById('edit-category-id').value; endpoint = `/api/expense-categories/${id}`; body = { name: document.getElementById('edit-category-name').value }; modal = modals.editCategory; }
        fetch(endpoint, { method: 'PUT', headers: {'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify(body) })
            .then(res => res.json()).then(data => { if (!data.errors) { fetchAllData(); modal.hide(); } else { alert('Error: ' + Object.values(data.errors).join('\\n')); }});
    }

    function handleDelete(type, id) {
    if (confirm(`Are you sure you want to delete this ${type}?`)) {
        const endpoint = `/api/${type === 'category' ? 'expense-categories' : 'expenses'}/${id}`;

        fetch(endpoint, { method: 'DELETE' })
            .then(response => {
                // For delete, we only check if the response was successful (status 204).
                // We DO NOT try to parse a JSON body.
                if (response.ok) {
                    fetchAllData(); // Refresh the data on success
                } else {
                    alert(`Failed to delete ${type}.`);
                }
            })
            .catch(error => console.error(`Error deleting ${type}:`, error));
    }
}

    // --- Initial Load ---
    fetchAllData();
});
</script>
@endsection