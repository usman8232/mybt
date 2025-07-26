<?php
include 'db_actions.php';
$username = "usman_umar_875";
$incomes = viewAllIncomes($username);

// Initialize variables with defaults
$totalAmount = 0;
$incomeCount = 0;
$averageAmount = 0;
$recentIncomes = [];

if ($incomes && $incomes->num_rows > 0) {
    while ($rows = $incomes->fetch_assoc()) {
        $totalAmount += $rows['amount'];
        $incomeCount++;
        $recentIncomes[] = $rows;
    }
    $averageAmount = $totalAmount / max(1, $incomeCount); // Prevent division by zero
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Report | <?= htmlspecialchars($username) ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2a7de1;
            --primary-dark: #1c5da4;
            --accent: #3d8bfd;
            --light-bg: #f8f9fc;
            --card-bg: #ffffff;
        }
        
        body {
            background: var(--light-bg);
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #2c3e50;
            padding-bottom: 2rem;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 2rem 2rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        
        .stat-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            background: var(--card-bg);
            border-top: 4px solid var(--primary);
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(42, 125, 225, 0.15);
        }
        
        .card-icon {
            background: rgba(42, 125, 225, 0.1);
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .income-table {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(225, 231, 240, 0.5);
        }
        
        .income-table thead {
            background: var(--primary);
            color: white;
        }
        
        .income-row:hover {
            background-color: rgba(240, 247, 255, 0.7) !important;
        }
        
        .income-amount {
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .user-avatar {
            width: 90px;
            height: 90px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .income-title {
            font-weight: 600;
            color: var(--primary);
            transition: all 0.2s;
        }
        
        .income-title:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .chart-container {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(225, 231, 240, 0.5);
            height: 100%;
        }
        
        .section-title {
            color: var(--primary);
            border-bottom: 2px solid rgba(225, 231, 240, 0.5);
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 0.75rem;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-radius: 2rem 2rem 0 0;
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
        
        .category-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .table-hover tbody tr {
            transition: background-color 0.2s;
        }
        
        @media (max-width: 768px) {
            .header {
                padding: 1.5rem 0;
                border-radius: 0 0 1.5rem 1.5rem;
            }
            
            .display-4 {
                font-size: 2.2rem;
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
                    <h1 class="display-4 fw-bold"><i class="bi bi-graph-up me-3"></i>Income Report</h1>
                    <p class="lead">Financial overview of your income sources</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=2a7de1&color=fff&size=128" 
                         alt="User Avatar" class="user-avatar rounded-circle">
                    <h4 class="mt-3 text-white"><?= htmlspecialchars($username) ?></h4>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon me-4">
                                <i class="bi bi-wallet2 text-primary fs-3"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-2">TOTAL INCOME</h5>
                                <h2 class="fw-bold">$<?php echo number_format($totalAmount, 2) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon me-4">
                                <i class="bi bi-graph-up text-primary fs-3"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-2">AVERAGE INCOME</h5>
                                <h2 class="fw-bold">$<?= number_format($averageAmount, 2) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="card-icon me-4">
                                <i class="bi bi-receipt text-primary fs-3"></i>
                            </div>
                            <div>
                                <h5 class="card-title text-muted mb-2">INCOME RECORDS</h5>
                                <h2 class="fw-bold"><?= $incomeCount ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income Table -->
        <div class="row mb-5">
            <div class="col">
                <div class="card border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0"><i class="bi bi-table me-2 text-primary"></i>Income Details</h3>
                            <a href="addincome.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Add Income
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 income-table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-tag me-1"></i> Category</th>
                                        <th><i class="bi bi-card-heading me-1"></i> Title</th>
                                        <th><i class="bi bi-card-text me-1"></i> Description</th>
                                        <th class="text-end"><i class="bi bi-currency-dollar me-1"></i> Amount</th>
                                        <th><i class="bi bi-calendar me-1"></i> Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($incomeCount > 0): ?>
                                        <?php foreach ($recentIncomes as $income): ?>
                                            <tr class="income-row">
                                                <td>
                                                    <span class="category-badge bg-primary bg-opacity-10 text-primary">
                                                        <?= htmlspecialchars($income['category'] ?? 'Salary') ?>
                                                    </span>
                                                </td>
                                                <td><span class="income-title"><?= htmlspecialchars($income['title']) ?></span></td>
                                                <td><?= htmlspecialchars($income['description']) ?></td>
                                                <td class="text-end income-amount">$<?= number_format($income['amount'], 2) ?></td>
                                                <td><?= date('M d, Y', strtotime($income['cr_date'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">
                                                <div class="text-center py-5">
                                                    <i class="bi bi-wallet display-1 text-muted mb-3"></i>
                                                    <h3>No Income Records Found</h3>
                                                    <p class="lead text-muted mb-4">Start adding income sources to see them listed here</p>
                                                    <a href="addincome.php" class="btn btn-primary btn-lg">
                                                        <i class="bi bi-plus-circle me-1"></i> Add Your First Income
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Summary -->
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="chart-container">
                    <h4 class="section-title"><i class="bi bi-pie-chart me-2"></i>Income Distribution</h4>
                    <div class="text-center">
                        <img src="https://quickchart.io/chart?c={type:'doughnut',data:{labels:['Salary','Freelance','Investments','Other'], datasets:[{data:[65,20,10,5],backgroundColor:['#2a7de1','#1c5da4','#3d8bfd','#6ea8ff']}]}}&width=400&height=300" 
                             alt="Income Distribution Chart" class="img-fluid rounded">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-container">
                    <h4 class="section-title"><i class="bi bi-activity me-2"></i>Monthly Income Trend</h4>
                    <div class="text-center">
                        <img src="https://quickchart.io/chart?c={type:'bar',data:{labels:['Jan','Feb','Mar','Apr','May','Jun'], datasets:[{label:'Income',data:[4200,3800,5100,4500,5800,6200],backgroundColor:'#2a7de1'}]}}&width=500&height=250" 
                             alt="Monthly Income Chart" class="img-fluid rounded">
                    </div>
                    <div class="mt-4">
                        <h5 class="fw-bold mb-3">Income Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Highest Income Month:</span>
                            <strong>June ($6,200)</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Lowest Income Month:</span>
                            <strong>February ($3,800)</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Quarterly Growth:</span>
                            <strong class="text-success">+24.5% ↗</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <a href="addincome.php" class="floating-btn btn btn-primary">
        <i class="bi bi-plus-lg fs-5"></i>
    </a>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5><i class="bi bi-piggy-bank me-2"></i>Income Tracker Pro</h5>
                    <p class="mb-0">Professional income tracking and financial reporting</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-1">Generated on: <?= date('F j, Y') ?></p>
                    <p class="mb-0">Report for: <?= htmlspecialchars($username) ?></p>
                </div>
            </div>
            <hr class="my-3 bg-light opacity-25">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> Blue Income Report System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>