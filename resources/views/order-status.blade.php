@extends('layouts.app')

@section('content')
    <h1 class="mb-4 fs-2">Order Status Screen</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h4>Pending</h4>
                </div>
                <div class="card-body" id="pending-orders" style="min-height: 400px;">
                    </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4>Processing</h4>
                </div>
                <div class="card-body" id="processing-orders" style="min-height: 400px;">
                    </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4>Ready for Pickup</h4>
                </div>
                <div class="card-body" id="ready-orders" style="min-height: 400px;">
                    </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get references to the columns
    const pendingCol = document.getElementById('pending-orders');
    const processingCol = document.getElementById('processing-orders');
    const readyCol = document.getElementById('ready-orders');

    function fetchOrders() {
        fetch('/api/orders')
            .then(response => response.json())
            .then(orders => {
                // Clear existing cards
                pendingCol.innerHTML = '';
                processingCol.innerHTML = '';
                readyCol.innerHTML = '';

                // Loop through orders and place them in the correct column
                orders.forEach(order => {
                    const orderCardHtml = `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">${order.order_number}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">${order.customer.name}</h6>
                                <p class="card-text">Total: <strong>₹${parseFloat(order.grand_total).toFixed(2)}</strong></p>
                            </div>
                        </div>
                    `;

                    switch (order.status) {
                        case 'pending':
                            pendingCol.innerHTML += orderCardHtml;
                            break;
                        case 'processing':
                            processingCol.innerHTML += orderCardHtml;
                            break;
                        case 'ready':
                            readyCol.innerHTML += orderCardHtml;
                            break;
                        // You can add cases for 'completed' and 'cancelled' if needed
                    }
                });
            })
            .catch(error => console.error('Error fetching orders:', error));
    }

    // Fetch orders when the page loads
    fetchOrders();

    // Optional: Auto-refresh the data every 30 seconds
    setInterval(fetchOrders, 30000);
});
</script>
@endsection