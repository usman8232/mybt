<?php
include 'db_actions.php';

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $currentDate = date("d-m-Y");
    $currentMonth = date("m");
    $currentYear = date("Y");

    if (SaveIncome($username, $title, $description, $amount, $currentDate, $currentMonth, $currentYear)) {
        $success_message = "Income record added successfully!";
    } else {
        $error_message = "Failed to add income record. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Income | Personal Finance Tracker</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .income-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      border: none;
    }
    .card-header {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      color: white;
      padding: 1.5rem;
      text-align: center;
      border-bottom: none;
    }
    .card-body {
      padding: 2rem;
    }
    .form-control {
      border-radius: 8px;
      padding: 12px 15px;
      border: 1px solid #ced4da;
    }
    .form-control:focus {
      border-color: #4facfe;
      box-shadow: 0 0 0 0.25rem rgba(79, 172, 254, 0.25);
    }
    .btn-submit {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }
    .btn-submit:hover {
      background: linear-gradient(to right, #3d9bed, #00d9e6);
      transform: translateY(-2px);
    }
    .input-group-text {
      background-color: #f8f9fa;
    }
    .amount-input {
      position: relative;
    }
    .currency-symbol {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      z-index: 5;
      font-weight: bold;
      color: #495057;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card income-card">
        <div class="card-header">
          <h3><i class="fas fa-money-bill-wave me-2"></i> Add Income Record</h3>
        </div>
        <div class="card-body">
          <?php if(isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <?php echo $success_message; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          
          <?php if(isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <?php echo $error_message; ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          
          <form action="addincome.php" method="post">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username" required>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="title" class="form-label">Income Title</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                <input type="text" class="form-control" name="title" id="title" placeholder="e.g. Salary, Bonus, Freelance" required>
              </div>
            </div>
            
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                <textarea class="form-control" name="description" id="description" rows="2" placeholder="Optional description"></textarea>
              </div>
            </div>
            
            <div class="mb-4">
              <label for="amount" class="form-label">Amount</label>
              <div class="amount-input">
                <span class="currency-symbol">$</span>
                <input type="number" class="form-control ps-4" name="amount" id="amount" placeholder="0.00" step="0.01" min="0" required>
              </div>
            </div>
            
            <div class="d-grid">
              <button type="submit" name="signup" class="btn btn-primary btn-submit">
                <i class="fas fa-plus-circle me-2"></i> Add Income
              </button>
            </div>
          </form>
        </div>
        <div class="card-footer text-center">
          <a href="dashboard.php" class="text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>