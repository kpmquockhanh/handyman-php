<?php
session_start();

$error = '';
$message = '';
$errors = [];

if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../../db.php';
    if (!isset($conn)) {
        $error = 'Database connection error';
    } else {
        $name = trim($_POST['name']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $terms = isset($_POST['remember']) ? $_POST['remember'] : false;

        // Enhanced validation
        if (empty($name)) {
            $errors['name'] = 'Name is required.';
        } elseif (strlen($name) < 3 || strlen($name) > 50) {
            $errors['name'] = 'Name must be between 3 and 50 characters.';
        } elseif (!preg_match('/^[a-zA-Z0-9\s]+$/', $name)) {
            $errors['name'] = 'Name can only contain letters, numbers, and spaces.';
        }

        // Enhanced email validation
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        // Enhanced password validation
        if (empty($password)) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password)) {
            $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number.';
        }

        if (!$terms) {
            $errors['terms'] = 'You must agree to the terms and conditions.';
        }

        if (empty($errors)) {
            try {
                // Check if email already exists
                $stmt = $conn->prepare('SELECT id FROM users WHERE email = :email');
                $stmt->execute(['email' => $email]);

                if ($stmt->fetch()) {
                    $error = 'This email is already registered.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $status = 'active'; // or 'pending' if email verification is needed

                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, status, created_at) 
                                          VALUES (:name, :email, :password, :status, NOW())");

                    $stmt->execute([
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashedPassword,
                        'status' => $status,
                    ]);

                    $message = "Registration successful! <a href='/auth/login.php'>Login here</a>";

                    // Optional: Automatically log in the user
                    // $_SESSION['user_id'] = $conn->lastInsertId();
                    // $_SESSION['user_name'] = $name;
                    // $_SESSION['role'] = 'user';
                    // header("Location: /index.php");
                    // exit();
                }
            } catch (PDOException $e) {
                $error = 'An error occurred. Please try again later.';
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
    <title>Register - Handyman Services</title>
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
                    <form method="POST" id="register-form" class="auth-form">
                        <h2 class="form-title">Create Account</h2>

                        <?php if ($error || !empty($errors)): ?>
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php
                            if ($error) {
                                echo htmlspecialchars($error);
                            } else {
                                echo implode('<br>', array_map('htmlspecialchars', $errors));
                            }
                            ?>
                        </div>
                        <?php endif; ?>

                        <?php if ($message): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $message; ?>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" id="name" placeholder="Enter your full name"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Create a password"
                                required>
                        </div>
                        <div>
                            <div class="password-requirements">
                                <ul>
                                    <li id="length-check">At least 8 characters</li>
                                    <li id="uppercase-check">One uppercase letter</li>
                                    <li id="lowercase-check">One lowercase letter</li>
                                    <li id="number-check">One number</li>
                                </ul>
                            </div>
                            <div class="password-strength">
                                <div class="password-strength-bar"></div>
                            </div>
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" name="remember" id="remember" required>
                            <label for="remember">I agree to the <a href="/terms">Terms of Service</a> and <a
                                    href="/privacy">Privacy Policy</a></label>
                        </div>

                        <button type="submit" class="submit-button">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>

                        <div class="auth-links">
                            <p>Already have an account? <a href="/auth/login.php">Sign In</a></p>
                        </div>
                    </form>
                </div>

                <div class="auth-image-section">
                    <img src="/assets/images/signup-image.png" alt="Register illustration">
                    <h2>Join Our Community</h2>
                    <p>Get access to trusted professionals and amazing service</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('register-form');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const strengthBar = document.querySelector('.password-strength-bar');

            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0;
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password)
                };

                Object.entries(checks).forEach(([check, passed]) => {
                    if (passed) {
                        strength += 25;
                        document.getElementById(`${check}-check`).style.color = '#4caf50';
                    } else {
                        document.getElementById(`${check}-check`).style.color = '#666';
                    }
                });

                strengthBar.style.width = `${strength}%`;
                strengthBar.style.backgroundColor =
                    strength <= 25 ? '#dc3545' :
                    strength <= 50 ? '#ffc107' :
                    strength <= 75 ? '#4caf50' : '#2575fc';

                return strength === 100;
            }

            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const errorMessages = [];

                // Name validation
                if (!nameInput.value.trim()) {
                    errorMessages.push('Name is required');
                    nameInput.classList.add('error');
                    isValid = false;
                }

                // Email validation
                if (!emailInput.value.trim()) {
                    errorMessages.push('Email is required');
                    emailInput.classList.add('error');
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                    errorMessages.push('Please enter a valid email address');
                    emailInput.classList.add('error');
                    isValid = false;
                }

                // Password validation
                if (!checkPasswordStrength(passwordInput.value)) {
                    errorMessages.push('Please meet all password requirements');
                    passwordInput.classList.add('error');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    const errorDiv = document.querySelector('.error-message') || document.createElement(
                        'div');
                    errorDiv.className = 'error-message';
                    errorDiv.innerHTML =
                        `<i class="fas fa-exclamation-circle"></i> ${errorMessages.join('<br>')}`;
                    form.insertBefore(errorDiv, form.firstChild);
                }
            });

            // Remove error styling on input
            [nameInput, emailInput, passwordInput].forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('error');
                    const errorDiv = document.querySelector('.error-message');
                    if (errorDiv) errorDiv.remove();
                });
            });
        });
    </script>

</html>
