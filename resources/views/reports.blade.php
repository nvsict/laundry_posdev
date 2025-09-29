@extends('layouts.app')

@section('content')
    <h1 class="mb-4 fs-2">Reports</h1>

    <div class="card mb-4">
        <div class="card-header"><h5>Purchase Report</h5></div>
        <div class="card-body">
            <form id="purchase-report-form" class="row g-3 align-items-end">
                <div class="col-md-4"><label for="purchase_start_date" class="form-label">Start Date</label><input type="date" class="form-control" id="purchase_start_date" required></div>
                <div class="col-md-4"><label for="purchase_end_date" class="form-label">End Date</label><input type="date" class="form-control" id="purchase_end_date" required></div>
                <div class="col-md-4"><button type="submit" class="btn btn-primary w-100">Generate Purchase Report</button></div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5>Sales Report</h5></div>
        <div class="card-body">
            <form id="sales-report-form" class="row g-3 align-items-end">
                <div class="col-md-4"><label for="sales_start_date" class="form-label">Start Date</label><input type="date" class="form-control" id="sales_start_date" required></div>
                <div class="col-md-4"><label for="sales_end_date" class="form-label">End Date</label><input type="date" class="form-control" id="sales_end_date" required></div>
                <div class="col-md-4"><button type="submit" class="btn btn-info w-100">Generate Sales Report</button></div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header"><h5>Expense Report</h5></div>
        <div class="card-body">
            <form id="expense-report-form" class="row g-3 align-items-end">
                <div class="col-md-4"><label for="expense_start_date" class="form-label">Start Date</label><input type="date" class="form-control" id="expense_start_date" required></div>
                <div class="col-md-4"><label for="expense_end_date" class="form-label">End Date</label><input type="date" class="form-control" id="expense_end_date" required></div>
                <div class="col-md-4"><button type="submit" class="btn btn-secondary w-100">Generate Expense Report</button></div>
            </form>
        </div>
    </div>

    <div id="report-results" class="mt-4" style="display: none;">
        <hr>
        <h4 id="report-title"></h4>
        <div class="row mb-3" id="summary-cards"></div>
        <div class="card">
            <div class="card-body">
                 <table class="table table-hover">
                    <thead id="report-table-head"></thead>
                    <tbody id="report-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchaseReportForm = document.getElementById('purchase-report-form');
    const salesReportForm = document.getElementById('sales-report-form');
    const expenseReportForm = document.getElementById('expense-report-form');
    const resultsSection = document.getElementById('report-results');
    const reportTitle = document.getElementById('report-title');
    const summaryCards = document.getElementById('summary-cards');
    const tableHead = document.getElementById('report-table-head');
    const tableBody = document.getElementById('report-table-body');

    purchaseReportForm.addEventListener('submit', (e) => { e.preventDefault(); generateReport('purchases', document.getElementById('purchase_start_date').value, document.getElementById('purchase_end_date').value); });
    salesReportForm.addEventListener('submit', (e) => { e.preventDefault(); generateReport('sales', document.getElementById('sales_start_date').value, document.getElementById('sales_end_date').value); });
    expenseReportForm.addEventListener('submit', (e) => { e.preventDefault(); generateReport('expenses', document.getElementById('expense_start_date').value, document.getElementById('expense_end_date').value); });

    function generateReport(type, startDate, endDate) {
        if (!startDate || !endDate) { alert('Please select both a start and end date.'); return; }
        const apiUrl = `/api/reports/${type}?start_date=${startDate}&end_date=${endDate}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.errors) { alert('Error: ' + Object.values(data.errors).join('\\n')); return; }

                resultsSection.style.display = 'block';
                const period = data.report_period.start_date ? `(${data.report_period.start_date} to ${data.report_period.end_date})` : `(${data.report_period.start} to ${data.report_period.end})`;

                if (type === 'purchases') {
                    reportTitle.innerText = `Purchase Report ${period}`;
                    summaryCards.innerHTML = `
                        <div class="col-md-6"><div class="card text-white bg-success"><div class="card-body"><h5 class="card-title">Total Purchases</h5><p class="card-text fs-4">${data.summary.total_purchases}</p></div></div></div>
                        <div class="col-md-6"><div class="card text-white bg-danger"><div class="card-body"><h5 class="card-title">Total Amount Spent</h5><p class="card-text fs-4">₹${parseFloat(data.summary.total_amount_spent).toFixed(2)}</p></div></div></div>`;
                    tableHead.innerHTML = `<tr><th>Ref #</th><th>Supplier ID</th><th>Date</th><th>Status</th><th>Total</th></tr>`;
                    tableBody.innerHTML = '';
                    data.data.forEach(p => { tableBody.innerHTML += `<tr><td>${p.reference_no}</td><td>${p.supplier_id}</td><td>${p.purchase_date}</td><td><span class="badge bg-primary">${p.status}</span></td><td>₹${parseFloat(p.total_amount).toFixed(2)}</td></tr>`; });
                } else if (type === 'sales') {
                    reportTitle.innerText = `Sales Report ${period}`;
                    summaryCards.innerHTML = `
                        <div class="col-md-6"><div class="card text-white bg-primary"><div class="card-body"><h5 class="card-title">Total Orders</h5><p class="card-text fs-4">${data.summary.total_orders}</p></div></div></div>
                        <div class="col-md-6"><div class="card text-white bg-info"><div class="card-body"><h5 class="card-title">Total Revenue</h5><p class="card-text fs-4">₹${parseFloat(data.summary.total_revenue).toFixed(2)}</p></div></div></div>`;
                    tableHead.innerHTML = `<tr><th>Order #</th><th>Customer</th><th>Date</th><th>Status</th><th>Total</th></tr>`;
                    tableBody.innerHTML = '';
                    data.data.forEach(o => { tableBody.innerHTML += `<tr><td>${o.order_number}</td><td>${o.customer.name}</td><td>${o.order_date}</td><td><span class="badge bg-warning">${o.status}</span></td><td>₹${parseFloat(o.grand_total).toFixed(2)}</td></tr>`; });
                } else if (type === 'expenses') {
                    reportTitle.innerText = `Expense Report ${period}`;
                    summaryCards.innerHTML = `
                        <div class="col-md-6"><div class="card text-white bg-warning"><div class="card-body"><h5 class="card-title">Total Expenses</h5><p class="card-text fs-4">${data.summary.total_expenses}</p></div></div></div>
                        <div class="col-md-6"><div class="card text-white bg-danger"><div class="card-body"><h5 class="card-title">Total Amount Spent</h5><p class="card-text fs-4">₹${parseFloat(data.summary.grand_total_spent).toFixed(2)}</p></div></div></div>`;
                    tableHead.innerHTML = `<tr><th>Date</th><th>Category</th><th>Amount</th><th>Description</th></tr>`;
                    tableBody.innerHTML = '';
                    data.data.forEach(ex => { tableBody.innerHTML += `<tr><td>${ex.expense_date}</td><td>${ex.expense_category.name}</td><td>₹${parseFloat(ex.amount).toFixed(2)}</td><td>${ex.description || ''}</td></tr>`; });
                }
            })
            .catch(error => console.error('Error fetching report:', error));
    }
});
</script>
@endsection