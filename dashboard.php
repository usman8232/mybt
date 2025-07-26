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
    <title>Budget Tracker Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6c63ff;
            --primary-light: #8b85ff;
            --primary-dark: #4d44db;
            --secondary: #4cc9f0;
            --income: #28a745;
            --expense: #dc3545;
            --light-bg: #f8f9fa;
            --dark-bg: #1a2035;
            --card-bg: #ffffff;
            --text-light: #f8f9fa;
            --text-dark: #212529;
            --sidebar-width: 280px;
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            overflow-x: hidden;
        }
        
        /* Sidebar styling */
        .sidebar {
            height: 100vh;
            background: linear-gradient(to bottom, var(--dark-bg), #1a2035e6);
            color: var(--text-light);
            position: fixed;
            width: var(--sidebar-width);
            z-index: 1000;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            transition: var(--transition);
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }
        
        .sidebar-brand i {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-: text;
            -webkit-text-fill-color: transparent;
            margin-right: 10px;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(108, 99, 255, 0.15);
        }
        
        .nav-link.active {
            border-left: 4px solid var(--primary);
        }
        
        .nav-link i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: var(--transition);
        }
        
        /* Card styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: var(--transition);
            overflow: hidden;
            background: var(--card-bg);
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .summary-card {
            border-top: 4px solid;
            position: relative;
            overflow: hidden;
        }
        
        .summary-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(255,255,255,0.1), transparent);
            z-index: 1;
            pointer-events: none;
        }
        
        .income-card {
            border-top-color: var(--income);
        }
        
        .expense-card {
            border-top-color: var(--expense);
        }
        
        .balance-card {
            border-top-color: var(--primary);
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .card-icon.income {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--income);
        }
        
        .card-icon.expense {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--expense);
        }
        
        .card-icon.balance {
            background-color: rgba(108, 99, 255, 0.15);
            color: var(--primary);
        }
        
        .transaction-income {
            border-left: 4px solid var(--income);
        }
        
        .transaction-expense {
            border-left: 4px solid var(--expense);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        /* Quick action buttons */
        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            border-radius: 12px;
            background: var(--card-bg);
            transition: var(--transition);
            text-align: center;
            color: var(--text-dark);
            height: 100%;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            background: linear-gradient(to bottom, var(--primary-light), var(--primary));
            color: white;
        }
        
        .quick-action-btn i {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        
        /* Chart placeholder */
        .chart-placeholder {
            background: linear-gradient(135deg, #f5f7ff, #e6e9ff);
            border-radius: 12px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                overflow: hidden;
            }
            
            .sidebar .nav-text {
                display: none;
            }
            
            .sidebar-header {
                padding: 15px 10px;
            }
            
            .sidebar-brand span {
                display: none;
            }
            
            .main-content {
                margin-left: 80px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
        
        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--expense);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }
        
        /* Transaction list */
        .transaction-item {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: var(--transition);
            cursor: pointer;
        }
        
        .transaction-item:hover {
            background-color: rgba(245, 247, 255, 0.7);
            transform: translateX(5px);
        }
        
        /* Stats highlight */
        .stats-highlight {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            -webkit-: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar p-3">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <i class="fas fa-wallet"></i>
                <span>Budget Tracker</span>
            </div>
        </div>
        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="viewAllIncomes.php">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="nav-text">Income</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="viewAllExpenses.php">
                    <i class="fas fa-receipt"></i>
                    <span class="nav-text">Expenses</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-pie"></i>
                    <span class="nav-text">Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-tags"></i>
                    <span class="nav-text">Categories</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <a class="nav-link" href="landingpage.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar bg-white rounded mb-4 shadow-sm">
            <div class="container-fluid">
                <h4 class="mb-0">Dashboard Overview</h4>
                <div class="d-flex align-items-center">
                    <div class="position-relative me-4">
                        <button class="btn position-relative p-0">
                            <i class="fas fa-bell text-muted fs-5"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>
                    <div class="dropdown">
                        <a class="dropdown-toggle text-decoration-none d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">UU</div>
                            <span class="d-none d-md-inline">usman_umar_875</span>
                            <i class="fas fa-chevron-down ms-2 fs-10"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="landingpage.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3 animate-fade">
                <div class="card summary-card income-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-3">TOTAL INCOME</h6>
                                <h3 class="text-success mb-0">$<?= number_format($totalIncome, 2) ?></h3>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-arrow-up text-success me-1"></i>
                                    <span class="stats-highlight">+12%</span> from last month
                                </small>
                            </div>
                            <div class="card-icon income">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 animate-fade delay-1">
                <div class="card summary-card expense-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-3">TOTAL EXPENSES</h6>
                                <h3 class="text-danger mb-0">$<?php echo number_format($totalExpense, 2) ?></h3>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-arrow-up text-danger me-1"></i>
                                    <span class="stats-highlight">+5%</span> from last month
                                </small>
                            </div>
                            <div class="card-icon expense">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 animate-fade delay-2">
                <div class="card summary-card balance-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-muted mb-3">CURRENT BALANCE</h6>
                                <h3 class="text-primary mb-0">$<?= number_format($balance, 2) ?></h3>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-piggy-bank text-primary me-1"></i>
                                    <span class="stats-highlight">$320 saved</span> this month
                                </small>
                            </div>
                            <div class="card-icon balance">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Quick Actions -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-3 animate-fade delay-1">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Financial Overview</h5>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary active">Monthly</button>
                                <button class="btn btn-sm btn-outline-primary">Quarterly</button>
                                <button class="btn btn-sm btn-outline-primary">Yearly</button>
                            </div>
                        </div>
                        <div class="chart-placeholder">
                            <div class="mb-4">
                                <i class="fas fa-chart-line text-primary mb-3" style="font-size: 3rem;"></i>
                                <h4>Income vs Expenses</h4>
                                <p class="text-muted">Visual representation of your financial data</p>
                            </div>
                            <div class="progress w-100" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 w-100">
                                <small class="text-success"><i class="fas fa-circle"></i> Income: 65%</small>
                                <small class="text-danger"><i class="fas fa-circle"></i> Expenses: 35%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3 animate-fade delay-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Quick Actions</h5>
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="addincome.php" class="quick-action-btn">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Add Income</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="add_expense.php" class="quick-action-btn">
                                    <i class="fas fa-minus-circle"></i>
                                    <span>Add Expense</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="quick-action-btn">
                                    <i class="fas fa-chart-pie"></i>
                                    <span>View Reports</span>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="printout.php" class="quick-action-btn">
                                    <i class="fas fa-file-export"></i>
                                    <span>Export Data</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row animate-fade delay-3">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Recent Income</h5>
                            <a href="viewAllIncomes.php" class="btn btn-sm btn-success">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="list-group">
                            <div class="transaction-item transaction-income">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="fas fa-briefcase me-2 text-success"></i> 
                                            Salary Payment
                                        </h6>
                                        <small class="text-muted">June 15, 2023</small>
                                    </div>
                                    <div class="text-success fw-bold">
                                        $3,000.00
                                    </div>
                                </div>
                            </div>
                            <div class="transaction-item transaction-income">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="fas fa-laptop-code me-2 text-success"></i> 
                                            Freelance Work
                                        </h6>
                                        <small class="text-muted">June 22, 2023</small>
                                    </div>
                                    <div class="text-success fw-bold">
                                        $750.00
                                    </div>
                                </div>
                            </div>
                            <div class="transaction-item transaction-income">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="fas fa-chart-line me-2 text-success"></i> 
                                            Investment Dividends
                                        </h6>
                                        <small class="text-muted">June 30, 2023</small>
                                    </div>
                                    <div class="text-success fw-bold">
                                        $500.00
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Recent Expenses</h5>
                            <a href="viewAllExpenses.php" class="btn btn-sm btn-danger">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="list-group">
                            <div class="transaction-item transaction-expense">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="fas fa-home me-2 text-danger"></i> 
                                            Rent Payment
                                        </h6>
                                        <small class="text-muted">June 1, 2023</small>
                                    </div>
                                    <div class="text-danger fw-bold">
                                        $1,200.00
                                    </div>
                                </div>
                            </div>
                            <div class="transaction-item transaction-expense">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="fas fa-shopping-cart me-2 text-danger"></i> 
                                            Grocery Shopping
                                        </h6>
                                        <small class="text-muted">June 15, 2023</small>
                                    </div>
                                    <div class="text-danger fw-bold">
                                        $350.00
                                    </div>
                                </div>
                            </div>
                            <div class="transaction-item transaction-expense">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1 d-flex align-items-center">
                                            <i class="fas fa-utensils me-2 text-danger"></i> 
                                            Dining Out
                                        </h6>
                                        <small class="text-muted">June 20, 2023</small>
                                    </div>
                                    <div class="text-danger fw-bold">
                                        $120.00
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <footer class="mt-5 pt-4 text-center text-muted">
            <p class="mb-0">Budget Tracker Pro &copy; 2023 | All Rights Reserved</p>
            <small>Last updated: July 24, 2023</small>
        </footer>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>