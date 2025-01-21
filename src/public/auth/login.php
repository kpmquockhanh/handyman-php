<?php
session_start();
$error = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../../db.php';
    if (!isset($conn)) {
        $error = "Database connection error";
    } else {
        // Sanitize inputs
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        
        if (empty($email) || empty($password)) {
            $error = "Please fill in all fields";
        } else {
            try {
                // Fetch user from database with status check
                $stmt = $conn->prepare("SELECT id, name, role, password, status FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Check if account is active
                    if ($user['status'] !== 'active') {
                        $error = "Your account is not active. Please contact support.";
                    } else {
                        // Login successful
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['user_name'] = $user['name'];
                        
                        // Handle remember me
                        if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                            $token = bin2hex(random_bytes(32));
                            setcookie('remember_token', $token, time() + (86400 * 30), "/"); // 30 days

                            // Store token in database
                            $stmt = $conn->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
                            $stmt->execute(['token' => $token, 'id' => $user['id']]);
                        }
                        
                        header("Location: /index.php");
                        exit();
                    }
                } else {
                    // Add delay to prevent brute force
                    sleep(1);
                    $error = "Invalid email or password!";
                }
            } catch (PDOException $e) {
                $error = $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Handyman Services</title>
    <link rel="stylesheet" href="/assets/layout.css">
    <link rel="stylesheet" href="/assets/auth-v2.css">
    <link href="/assets/fontawesome/css/all.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
          rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/logov3.jpeg">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/logov3.jpeg">
</head>

<body>
    <main>
        <div class="auth-container">
            <div class="auth-content">
                <div class="auth-form-section">
                    <form method="POST" id="login-form" class="auth-form">
                        <h2 class="form-title">Welcome Back!</h2>
                        
                        <?php if ($error): ?>
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="submit-button">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </button>

                        <div class="auth-links">
                            <!-- <a href="/auth/forgot-password.php">Forgot Password?</a> -->
                            <p>Don't have an account? <a href="/auth/register.php">Sign Up</a></p>
                        </div>

                        <div class="social-login">
                            <p>Or continue with</p>
                            <div class="social-buttons">
                                <a href="#" class="social-button"><i class="fab fa-google"></i></a>
                                <a href="#" class="social-button"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-button"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="auth-image-section">
                    <img src="/assets/images/signin-image2.png" alt="Login illustration">
                    <h2>Quality Service at Your Fingertips</h2>
                    <p>Connect with skilled professionals for all your home improvement needs</p>
                </div>
            </div>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('login-form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        // Show/hide password functionality
        const togglePassword = document.createElement('i');
        togglePassword.className = 'fas fa-eye password-toggle';
        togglePassword.setAttribute('title', 'Show password');
        passwordInput.parentElement.appendChild(togglePassword);

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.className = `fas fa-eye${type === 'password' ? '' : '-slash'} password-toggle`;
            this.setAttribute('title', `${type === 'password' ? 'Show' : 'Hide'} password`);
        });

        // Add focus effect for icons
        [emailInput, passwordInput].forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('i:not(.password-toggle)').style.color = '#2575fc';
            });
            
            input.addEventListener('blur', function() {
                if (!this.classList.contains('error')) {
                    this.parentElement.querySelector('i:not(.password-toggle)').style.color = '#6c757d';
                }
            });
        });

        // Form validation with better UX
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const errorMessages = [];

            if (!emailInput.value.trim()) {
                errorMessages.push('Email is required');
                emailInput.classList.add('error');
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                errorMessages.push('Please enter a valid email address');
                emailInput.classList.add('error');
                isValid = false;
            }

            if (!passwordInput.value.trim()) {
                errorMessages.push('Password is required');
                passwordInput.classList.add('error');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                const errorDiv = document.querySelector('.error-message') || document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorMessages.join('<br>')}`;
                form.insertBefore(errorDiv, form.firstChild);
            }
        });

        // Remove error styling on input
        [emailInput, passwordInput].forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorDiv = document.querySelector('.error-message');
                if (errorDiv) errorDiv.remove();
            });
        });
    });
    </script>
</body>
</html>