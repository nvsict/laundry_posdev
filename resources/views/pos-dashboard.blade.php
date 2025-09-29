@extends('layouts.app')
@section('content')
<style>
    .kpi-card { color: white; border-radius: .5rem; padding: 1.5rem; }
    .kpi-card h5 { font-size: 1rem; opacity: 0.9; }
    .kpi-card .count { font-size: 2.5rem; font-weight: bold; }
    .bg-gradient-pending { background-image: linear-gradient(to right, #6a82fb, #fc5c7d); }
    .bg-gradient-processing { background-image: linear-gradient(to right, #7f5a83, #0d324d); }
    .bg-gradient-ready { background-image: linear-gradient(to right, #ff8c00, #ffc928); }
    .bg-gradient-completed { background-image: linear-gradient(to right, #00b09b, #96c93d); }
</style>
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3"><div class="kpi-card bg-gradient-pending"><h5>Pending Orders</h5><div class="count" id="card-pending">...</div></div></div>
        <div class="col-lg-3 col-md-6 mb-3"><div class="kpi-card bg-gradient-processing"><h5>Processing Orders</h5><div class="count" id="card-processing">...</div></div></div>
        <div class="col-lg-3 col-md-6 mb-3"><div class="kpi-card bg-gradient-ready"><h5>Ready to Deliver</h5><div class="count" id="card-ready">...</div></div></div>
        <div class="col-lg-3 col-md-6 mb-3"><div class="kpi-card bg-gradient-completed"><h5>Completed Orders</h5><div class="count" id="card-completed">...</div></div></div>
    </div>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-2 mb-md-0">Today's Orders</h4>
            <div class="d-flex" style="max-width: 500px;">
                <input type="text" id="delivery-search" class="form-control me-2" placeholder="Search...">
                <select id="delivery-status-filter" class="form-select"><option value="">All Statuses</option><option value="pending">Pending</option><option value="processing">Processing</option><option value="ready">Ready</option><option value="completed">Completed</option></select>
            </div>
        </div>
        <div class="card-body"><table class="table table-hover"><thead><tr><th>Order #</th><th>Customer</th><th>Status</th><th>Total</th></tr></thead><tbody id="deliveries-table-body"></tbody></table></div>
    </div>
    <div class="row">
        <div class="col-lg-4 mb-4"><div class="card"><div class="card-body"><h5 class="card-title">Status Overview</h5><canvas id="statusOverviewChart"></canvas></div></div></div>
        <div class="col-lg-4 mb-4"><div class="card"><div class="card-body"><h5 class="card-title">Payment Overview</h5><canvas id="paymentOverviewChart"></canvas></div></div></div>
    </div>
     <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body"><h5 class="card-title">This Month: Revenue vs Expense</h5><canvas id="revenueExpenseChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('delivery-search');
    const statusFilter = document.getElementById('delivery-status-filter');
    let statusChart, paymentChart, revenueExpenseChart;

    function renderDashboard(data) {
        // Cards
        document.getElementById('card-pending').innerText = data.cards.pending;
        document.getElementById('card-processing').innerText = data.cards.processing;
        document.getElementById('card-ready').innerText = data.cards.ready;
        document.getElementById('card-completed').innerText = data.cards.completed;

        // Deliveries Table
        const deliveriesTable = document.getElementById('deliveries-table-body');
        deliveriesTable.innerHTML = '';
        if (data.todays_deliveries.length === 0) {
            deliveriesTable.innerHTML = '<tr><td colspan="4" class="text-center">No orders found for today.</td></tr>';
        } else {
            data.todays_deliveries.forEach(order => {
                deliveriesTable.innerHTML += `<tr><td>${order.order_number}</td><td>${order.customer.name}</td><td><span class="badge bg-primary">${order.status}</span></td><td>₹${parseFloat(order.grand_total).toFixed(2)}</td></tr>`;
            });
        }
        
        // Charts
        if(statusChart) statusChart.destroy();
        statusChart = new Chart(document.getElementById('statusOverviewChart').getContext('2d'), { type: 'doughnut', data: { labels: data.charts.status_overview.labels, datasets: [{ data: data.charts.status_overview.data, backgroundColor: ['#ffc107', '#6f42c1', '#17a2b8', '#28a745'] }] } });

        if(paymentChart) paymentChart.destroy();
        paymentChart = new Chart(document.getElementById('paymentOverviewChart').getContext('2d'), { type: 'doughnut', data: { labels: data.charts.payment_overview.labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)), datasets: [{ data: data.charts.payment_overview.data, backgroundColor: ['#dc3545', '#0d6efd'] }] } });

        if(revenueExpenseChart) revenueExpenseChart.destroy();
        revenueExpenseChart = new Chart(document.getElementById('revenueExpenseChart').getContext('2d'), { type: 'bar', data: { labels: data.charts.payment_vs_expense.labels, datasets: [{ label: 'Revenue (₹)', data: data.charts.payment_vs_expense.revenue, backgroundColor: 'rgba(75, 192, 192, 0.6)' }, { label: 'Expense (₹)', data: data.charts.payment_vs_expense.expenses, backgroundColor: 'rgba(255, 99, 132, 0.6)' }] } });
    }

    function fetchData() {
        const search = searchInput.value;
        const status = statusFilter.value;
        const url = `/api/dashboard?search=${search}&status=${status}`;
        fetch(url).then(res => res.json()).then(renderDashboard);
    }
    
    let debounceTimer;
    searchInput.addEventListener('keyup', () => { clearTimeout(debounceTimer); debounceTimer = setTimeout(fetchData, 500); });
    statusFilter.addEventListener('change', fetchData);

    fetchData(); // Initial load
});
</script>
@endsection