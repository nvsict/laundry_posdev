@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-4 fs-2">Orders List</h1>
        <a href="/pos" class="btn btn-primary">Create New Order</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead><tr><th>Order #</th><th>Customer</th><th>Date</th><th>Status</th><th>Payment</th><th>Total</th><th>Actions</th></tr></thead>
                <tbody id="orders-table-body"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Order: <span id="edit-order-number"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-order-form">
                        <input type="hidden" id="edit-order-id">
                        <div class="mb-3">
                            <label for="edit-status" class="form-label">Order Status</label>
                            <select class="form-select" id="edit-status">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="ready">Ready</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-payment-status" class="form-label">Payment Status</label>
                            <select class="form-select" id="edit-payment-status">
                                <option value="unpaid">Unpaid</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update-order-btn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('orders-table-body');
    const editOrderModal = new bootstrap.Modal(document.getElementById('editOrderModal'));

    function renderRow(order) {
        let statusBadgeColor = 'secondary';
        if (order.status === 'completed') statusBadgeColor = 'success';
        if (order.status === 'pending') statusBadgeColor = 'warning';
        if (order.status === 'processing') statusBadgeColor = 'info';
        if (order.status === 'cancelled') statusBadgeColor = 'danger';
        let paymentBadgeColor = order.payment_status === 'paid' ? 'success' : 'danger';
        
        // Data attributes for the edit button
        const dataset = `data-id="${order.id}" data-number="${order.order_number}" data-status="${order.status}" data-payment="${order.payment_status}"`;

        return `
            <tr id="order-${order.id}">
                <td><strong>${order.order_number}</strong></td>
                <td>${order.customer.name}</td>
                <td>${order.order_date}</td>
                <td><span class="badge bg-${statusBadgeColor}">${order.status}</span></td>
                <td><span class="badge bg-${paymentBadgeColor}">${order.payment_status}</span></td>
                <td>₹${parseFloat(order.grand_total).toFixed(2)}</td>
                <td>
    <button class="btn btn-sm btn-info edit-btn" ${dataset}>Edit</button>
    <a href="/orders/${order.id}/invoice" target="_blank" class="btn btn-sm btn-secondary">Invoice</a>
    <a href="/orders/${order.id}/receipt" target="_blank" class="btn btn-sm btn-light">Receipt</a>
</td>
            </tr>
        `;
    }

    function fetchOrders() {
        fetch('/api/orders').then(response => response.json()).then(data => {
            tableBody.innerHTML = '';
            data.forEach(order => tableBody.innerHTML += renderRow(order));
        });
    }

    // --- NEW: Handle Edit and Update clicks ---
    tableBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('edit-btn')) {
            const ds = event.target.dataset;
            document.getElementById('edit-order-id').value = ds.id;
            document.getElementById('edit-order-number').innerText = ds.number;
            document.getElementById('edit-status').value = ds.status;
            document.getElementById('edit-payment-status').value = ds.payment;
            editOrderModal.show();
        }
    });

    document.getElementById('update-order-btn').addEventListener('click', function() {
        const orderId = document.getElementById('edit-order-id').value;
        const orderData = {
            status: document.getElementById('edit-status').value,
            payment_status: document.getElementById('edit-payment-status').value,
        };

        fetch(`/api/orders/${orderId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(orderData)
        })
        .then(res => res.json()).then(data => {
            if (data.errors) {
                alert('Error: ' + Object.values(data.errors).join('\\n'));
            } else {
                // To keep it simple, we just refetch all orders to see the update
                fetchOrders();
                editOrderModal.hide();
            }
        });
    });

    fetchOrders();
});
</script>
@endsection