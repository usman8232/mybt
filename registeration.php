<?php
include 'db_actions.php';

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $gender   = $_POST['gender'];
    $status   = "active";

    if (SaveUserInfo($username, $name, $email, $password, $gender, $status)) {
        $success_message = "User registered successfully!";
    } else {
        $error_message = "Failed to register user. Username or email may already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Finance Tracker</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #8b5cf6;
            --light: #f8fafc;
            --dark: #0f172a;
            --gray: #94a3b8;
        }
        
        body {
            background-color: #f1f5f9;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-container {
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
        }
        
        .auth-card {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
            background: white;
        }
        
        .auth-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-body {
            padding: 2.5rem;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }
        
        .input-group-text {
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border: none;
            padding: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            color: var(--gray);
        }
        
        .gender-options {
            display: flex;
            gap: 12px;
        }
        
        .gender-option {
            flex: 1;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: var(--gray);
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .divider::before {
            margin-right: 1rem;
        }
        
        .divider::after {
            margin-left: 1rem;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray);
        }
        
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .invalid-feedback {
            font-size: 0.85rem;
        }

        /* New Register Now button style */
        .btn-register {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 242, 254, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            width: 100%;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #3a8fd9 0%, #00c8d6 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 242, 254, 0.4);
            color: white;
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register i {
            margin-right: 8px;
        }

        /* Login link button style */
        .btn-login-link {
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 12px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            width: 100%;
        }

        .btn-login-link:hover {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-dark);
        }

        .btn-login-link i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h3 class="mb-1"><i class="fas fa-wallet me-2"></i> Finance Tracker</h3>
            <p class="mb-0">Create your account</p>
        </div>
        <div class="auth-body">
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
            
            <form action="registeration.php" method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="username" id="username" placeholder="johndoe" required>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        <input type="text" class="form-control" name="name" id="name" placeholder="John Doe" required>
                        <div class="invalid-feedback">
                            Please provide your full name.
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" id="email" placeholder="john@example.com" required>
                        <div class="invalid-feedback">
                            Please provide a valid email address.
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required minlength="8">
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
                        <div class="invalid-feedback">
                            Password must be at least 8 characters.
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 position-relative">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="confirm_password" placeholder="••••••••" required>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                        <div class="invalid-feedback">
                            Passwords must match.
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Gender</label>
                    <div class="gender-options">
                        <div class="form-check gender-option">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male" required>
                            <label class="form-check-label" for="male">
                                <i class="fas fa-male me-1"></i> Male
                            </label>
                        </div>
                        <div class="form-check gender-option">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                            <label class="form-check-label" for="female">
                                <i class="fas fa-female me-1"></i> Female
                            </label>
                        </div>
                        <div class="form-check gender-option">
                            <input class="form-check-input" type="radio" name="gender" id="other" value="Other">
                            <label class="form-check-label" for="other">
                                <i class="fas fa-user me-1"></i> Other
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" name="signup" class="btn btn-register">
                        <i class="fas fa-user-plus"></i> Register Now
                    </button>
                    <a href="login.php" class="btn btn-login-link">
                        <i class="fas fa-sign-in-alt"></i> Already have an account? Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.parentNode.querySelector('.password-toggle');
        if (field.type === "password") {
            field.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
    
    // Form validation
    (function () {
        'use strict'
        
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')
        
        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                // Check password match
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('confirm_password');
                
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords must match");
                    confirmPassword.classList.add('is-invalid');
                } else {
                    confirmPassword.setCustomValidity('');
                    confirmPassword.classList.remove('is-invalid');
                }
                
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
        
        // Real-time password match checking
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            if (this.value !== password && this.value !== '') {
                this.setCustomValidity("Passwords must match");
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
    })()
</script>
</body>
</html>