<?php
include 'db_actions.php';
session_start();
$username = $_SESSION["user_id"] ?? "usman_umar_875"; // Fallback for testing

// Income Calculations
$incomes = viewAllIncomes($username);
$totalIncome = 0;
$incomeCount = 0;
$recentIncomes = [];
$incomeCategories = [];

if ($incomes && $incomes->num_rows > 0) {
    while ($rows = $incomes->fetch_assoc()) {
        $totalIncome += $rows['amount'];
        $incomeCount++;
        $recentIncomes[] = $rows;
    }
    $averageIncome = $totalIncome / max(1, $incomeCount);
}

// Expense Calculations
$expenses = viewAllExpense($username);
$totalExpense = 0;
$expenseCount = 0;
$recentExpenses = [];
$expenseCategories = [];

if ($expenses && $expenses->num_rows > 0) {
    while ($rows = $expenses->fetch_assoc()) {
        $totalExpense += $rows['amount'];
        $expenseCount++;
        $recentExpenses[] = $rows;
        $expenseCategories[$rows['title']] = ($expenseCategories[$rows['title']] ?? 0) + $rows['amount'];
    }
    $averageExpense = $totalExpense / max(1, $expenseCount);
}

// Balance Calculation
$balance = $totalIncome - $totalExpense;
$balanceClass = ($balance >= 0) ? 'balance-positive' : 'balance-negative';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report | <?= htmlspecialchars($username) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2a7de1;
            --primary-dark: #1c5da4;
            --danger: #dc3545;
            --success: #28a745;
            --teal: #20c997;
            --light-bg: #f8f9fc;
            --card-bg: #ffffff;
        }
        
        body {
            background: var(--light-bg);
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding-bottom: 2rem;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 2rem 2rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: none;
            background: var(--card-bg);
            border-top: 4px solid var(--primary);
            height: 100%;
        }
        
        .income-card {
            border-top-color: var(--success);
        }
        
        .expense-card {
            border-top-color: var(--danger);
        }
        
        .table-container {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .income-amount {
            font-weight: 600;
            color: var(--success);
        }
        
        .expense-amount {
            font-weight: 600;
            color: var(--danger);
        }
        
        .balance-positive {
            color: var(--success);
        }
        
        .balance-negative {
            color: var(--danger);
        }
        
        .category-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .floating-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(42, 125, 225, 0.4);
            z-index: 1000;
        }
        
        @media print {
            .no-print, .floating-btn {
                display: none;
            }
            body {
                padding: 0;
                background: white;
            }
            .header {
                border-radius: 0;
                margin-bottom: 1rem;
                padding: 1rem 0;
            }
            .stat-card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold"><i class="bi bi-file-earmark-text me-3"></i>Financial Report</h1>
                    <p class="lead">Complete overview of income and expenses</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=2a7de1&color=fff&size=128" 
                         alt="User Avatar" class="rounded-circle" style="width:80px;height:80px;border:3px solid white">
                    <h4 class="mt-2 text-white"><?= htmlspecialchars($username) ?></h4>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card income-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4" style="background:rgba(40,167,69,0.1);width:60px;height:60px;border-radius:12px;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-arrow-down-circle fs-3 text-success"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-2">TOTAL INCOME</h5>
                                <h2 class="fw-bold text-success">$<?= number_format($totalIncome, 2) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card expense-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4" style="background:rgba(220,53,69,0.1);width:60px;height:60px;border-radius:12px;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-arrow-up-circle fs-3 text-danger"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-2">TOTAL EXPENSE</h5>
                                <h2 class="fw-bold text-danger">$<?= number_format($totalExpense, 2) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4" style="background:rgba(42,125,225,0.1);width:60px;height:60px;border-radius:12px;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-wallet fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-2">NET BALANCE</h5>
                                <h2 class="fw-bold <?= $balanceClass ?>">$<?= number_format($balance, 2) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income Section -->
        <div class="table-container">
            <div class="p-3 bg-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-arrow-down-circle text-success me-2"></i>Income Details</h3>
                <span class="badge bg-success"><?= $incomeCount ?> records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th><i class="bi bi-tag me-1"></i> Category</th>
                            <th><i class="bi bi-card-heading me-1"></i> Title</th>
                            <th><i class="bi bi-card-text me-1"></i> Description</th>
                            <th class="text-end"><i class="bi bi-currency-rupee me-1"></i> Amount</th>
                            <th><i class="bi bi-calendar me-1"></i> Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($incomeCount > 0): ?>
                            <?php foreach ($recentIncomes as $income): ?>
                                <tr>
                                    <td>
                                        <span class="category-badge bg-success bg-opacity-10 text-success">
                                            <?= htmlspecialchars($income['category'] ?? 'Salary') ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($income['title']) ?></td>
                                    <td><?= htmlspecialchars($income['description']) ?></td>
                                    <td class="text-end income-amount">₹<?= number_format($income['amount'], 2) ?></td>
                                    <td><?= date('M d, Y', strtotime($income['cr_date'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-wallet text-muted" style="font-size:3rem"></i>
                                    <h4 class="mt-3">No Income Records</h4>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Expense Section -->
        <div class="table-container">
            <div class="p-3 bg-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="bi bi-arrow-up-circle text-danger me-2"></i>Expense Details</h3>
                <span class="badge bg-danger"><?= $expenseCount ?> records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th><i class="bi bi-tag me-1"></i> Title</th>
                            <th><i class="bi bi-card-text me-1"></i> Description</th>
                            <th class="text-end"><i class="bi bi-currency-rupee me-1"></i> Amount</th>
                            <th><i class="bi bi-calendar me-1"></i> Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($expenseCount > 0): ?>
                            <?php foreach ($recentExpenses as $expense): ?>
                                <tr>
                                    <td><?= htmlspecialchars($expense['title']) ?></td>
                                    <td><?= htmlspecialchars($expense['description']) ?></td>
                                    <td class="text-end expense-amount">₹<?= number_format($expense['amount'], 2) ?></td>
                                    <td><?= date('M d, Y', strtotime($expense['date'] ?? $expense['cr_date'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="bi bi-receipt text-muted" style="font-size:3rem"></i>
                                    <h4 class="mt-3">No Expense Records</h4>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="table-container">
                    <div class="p-3 bg-white">
                        <h4><i class="bi bi-pie-chart text-primary me-2"></i>Income Distribution</h4>
                        <div class="text-center py-3">
                            <?php if (!empty($incomeCategories)): ?>
                                <img src="https://quickchart.io/chart?c={type:'doughnut',data:{labels:<?= urlencode(json_encode(array_keys($incomeCategories))) ?>,datasets:[{data:<?= urlencode(json_encode(array_values($incomeCategories))) ?>,backgroundColor:['#28a745','#20c997','#2a7de1','#6ea8ff']}]}&width=400&height=300" 
                                     alt="Income Distribution" class="img-fluid">
                            <?php else: ?>
                                <p class="text-muted py-4">No income data available for chart</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="table-container">
                    <div class="p-3 bg-white">
                        <h4><i class="bi bi-bar-chart text-primary me-2"></i>Expense Distribution</h4>
                        <div class="text-center py-3">
                            <?php if (!empty($expenseCategories)): ?>
                                <img src="https://quickchart.io/chart?c={type:'doughnut',data:{labels:<?= urlencode(json_encode(array_keys($expenseCategories))) ?>,datasets:[{data:<?= urlencode(json_encode(array_values($expenseCategories))) ?>,backgroundColor:['#dc3545','#fd7e14','#ffc107','#6610f2']}]}&width=400&height=300" 
                                     alt="Expense Distribution" class="img-fluid">
                            <?php else: ?>
                                <p class="text-muted py-4">No expense data available for chart</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="floating-btn btn btn-primary no-print">
        <i class="bi bi-printer fs-5"></i>
    </button>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-piggy-bank me-2"></i>Financial Tracker</h5>
                    <p class="mb-0">Complete money management solution</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1">Generated on: <?= date('F j, Y g:i a') ?></p>
                    <p class="mb-0">User: <?= htmlspecialchars($username) ?></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>