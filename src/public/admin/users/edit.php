<?php
session_start();
require '../../../db.php';
include '../../../auth.php';

// Fetch user details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    try {
        $conn->beginTransaction();
        
        // Basic update query
        $sql = "UPDATE users SET name = :name, email = :email, role = :role, status = :status";
        $params = [
            'name' => $name, 
            'email' => $email, 
            'role' => $role, 
            'status' => $status,
            'id' => $id
        ];
        
        // If password is being updated
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                throw new Exception("Passwords do not match!");
            }
            if (strlen($new_password) < 6) {
                throw new Exception("Password must be at least 6 characters long!");
            }
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params['password'] = $hashed_password;
        }
        
        $sql .= " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        
        $conn->commit();
        $_SESSION['success_message'] = "User updated successfully!";
        header("Location: /admin/users");
        exit();
        
    } catch (Exception $e) {
        $conn->rollBack();
        $error_message = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
    <?php include '../../../layouts/admin_edit_styles.php'; ?>
    <style>
        .password-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .error-message {
            color: #dc3545;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            background-color: #ffe6e6;
        }
        .form-info {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="page-header">
    <h1>Edit User</h1>
</div>

<?php if (isset($error_message)): ?>
    <div class="error-message">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>

<form method="POST" class="edit-form">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            <option value="suspended" <?php echo $user['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
        </select>
    </div>
    
    <div class="password-section">
        <h3>Change Password</h3>
        <p class="form-info">Leave password fields empty if you don't want to change it.</p>
        
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" minlength="6">
            <p class="form-info">Minimum 6 characters</p>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>
    </div>
    
    <div class="button-group">
        <button type="submit" name="update_user" class="submit-button">Update User</button>
        <a href="index.php" class="cancel-button">Back to Manage Users</a>
    </div>
</form>

<?php include '../../../layouts/admin_footer_button.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    
    form.addEventListener('submit', function(e) {
        if (newPassword.value || confirmPassword.value) {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            if (newPassword.value.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        }
    });
});
</script>

</body>
</html>