<?php
include 'db_actions.php'; //include database file

$username = "usman_umar_875"; //username for which expenses are to be viewed
$expenses = viewAllExpense($username);

// Calculate statistics
$totalAmount = 0;
$expenseCount = 0;
if ($expenses && $expenses->num_rows > 0) {
    $expenseData = [];
    while ($rows = $expenses->fetch_assoc()) {
        $totalAmount += $rows['amount'];
        $expenseCount++;
        $expenseData[] = $rows;
    }
    $averageAmount = $totalAmount / $expenseCount;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report | <?php echo $username; ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2b5876;
            --secondary-color: #4e4376;
            --accent-color: #00c6fb;
            --teal-color: #20c997;
            --light-bg: #f8fafc;
            --dark-text: #2c3e50;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            padding-top: 20px;
            padding-bottom: 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            border: none;
            background-color: white;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .stat-card {
            background: linear-gradient(120deg, var(--accent-color), #0575e6);
            color: white;
            border: none;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }
        .table thead {
            background: var(--primary-color);
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 198, 251, 0.05);
        }
        .expense-row {
            transition: all 0.2s;
        }
        .expense-amount {
            font-weight: 600;
            color: var(--teal-color);
        }
        .expense-title {
            font-weight: 600;
            color: var(--dark-text);
        }
        .user-avatar {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .footer {
            margin-top: 3rem;
            padding: 1.5rem 0;
            color: #7f8c8d;
            text-align: center;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .badge-teal {
            background-color: var(--teal-color);
        }
        .text-teal {
            color: var(--teal-color);
        }
        .btn-primary-custom {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        .btn-primary-custom:hover {
            background-color: #00b5e6;
            border-color: #00b5e6;
        }
        .btn-add-expense {
            background-color: var(--teal-color);
            border-color: var(--teal-color);
            color: white;
        }
        .btn-add-expense:hover {
            background-color: #1aa57a;
            border-color: #1aa57a;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold"><i class="bi bi-cash-stack me-3"></i>Expense Report</h1>
                    <p class="lead">Detailed overview of your spending activities</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=0D8ABC&color=fff&size=128" 
                         alt="User Avatar" class="user-avatar rounded-circle">
                    <h4 class="mt-2 text-white"><?php echo $username; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Action Button Row -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="add_expense.php" class="btn btn-add-expense">
                    <i class="bi bi-plus-circle me-2"></i> Add New Expense
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h2><i class="bi bi-wallet2"></i></h2>
                        <h5 class="card-title">Total Expenses</h5>
                        <h3 class="display-4 fw-bold"><?php echo $expenseCount; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h2><i class="bi bi-currency-dollar"></i></h2>
                        <h5 class="card-title">Total Amount</h5>
                        <h3 class="display-4 fw-bold">$<?php echo number_format($totalAmount, 2); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h2><i class="bi bi-graph-up"></i></h2>
                        <h5 class="card-title">Average Expense</h5>
                        <h3 class="display-4 fw-bold">$<?php echo number_format($averageAmount, 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Table -->
        <div class="table-container">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-list-check me-2"></i>Expense Details</h3>
                <a href="add_expense.php" class="btn btn-sm btn-add-expense">
                    <i class="bi bi-plus me-1"></i> Add Expense
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><i class="bi bi-person me-1"></i> Username</th>
                            <th><i class="bi bi-tag me-1"></i> Title</th>
                            <th><i class="bi bi-card-text me-1"></i> Description</th>
                            <th class="text-end"><i class="bi bi-currency-dollar me-1"></i> Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($expenses && $expenses->num_rows > 0): ?>
                            <?php foreach ($expenseData as $row): ?>
                                <tr class="expense-row">
                                    <td><?php echo $row['username']; ?></td>
                                    <td class="expense-title"><?php echo $row['title']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td class="text-end expense-amount">$<?php echo number_format($row['amount'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="bi bi-receipt-cutoff" style="font-size: 3rem; color: #bdc3c7;"></i>
                                        <h4 class="mt-3">No expenses found</h4>
                                        <p class="text-muted">Start adding expenses to see them listed here</p>
                                        <a href="add_expense.php" class="btn btn-add-expense mt-3">
                                            <i class="bi bi-plus-circle me-2"></i> Add Your First Expense
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-pie-chart me-2"></i>Expense Distribution</h5>
                        <p class="text-muted">Visual representation of your spending</p>
                        <div class="d-flex justify-content-center">
                            <div class="bg-light rounded p-4">
                                <img src="https://quickchart.io/chart?c={type:'doughnut',data:{labels:['Food','Transport','Entertainment','Utilities'], datasets:[{data:[30,25,20,25],backgroundColor:['#2b5876','#4e4376','#00c6fb','#20c997']}]}}" 
                                     alt="Expense Chart" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-calendar-check me-2"></i>Recent Activity</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Coffee at Starbucks</h6>
                                    <small class="text-muted">Today, 10:30 AM</small>
                                </div>
                                <span class="badge badge-teal rounded-pill">$5.75</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Office Supplies</h6>
                                    <small class="text-muted">Yesterday, 2:15 PM</small>
                                </div>
                                <span class="badge badge-teal rounded-pill">$24.99</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Monthly Internet Bill</h6>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                                <span class="badge badge-teal rounded-pill">$69.99</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p class="mb-0"><i class="bi bi-layers"></i> Expense Report System &copy; <?php echo date('Y'); ?></p>
            <small>Generated on <?php echo date('F j, Y, g:i a'); ?></small>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>