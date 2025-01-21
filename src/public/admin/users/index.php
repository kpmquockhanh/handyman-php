<?php

session_start();
require '../../../db.php';
include '../../../auth.php';
if (!isset($conn)) exit();
// Add a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        echo "<p class='error'>Email already exists!</p>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, role, password, status) VALUES (:name, :email, :role, :password, :status)");
        $stmt->execute([
            'name' => $name, 
            'email' => $email, 
            'role' => $role, 
            'password' => $password,
            'status' => $status
        ]);
        echo "<p class='success'>User added successfully!</p>";
    }
}

// Delete a user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    echo "<p class='success'>User deleted successfully!</p>";
}

// Fetch all users
$stmt = $conn->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
</head>
<body>
    <h1>Manage Users</h1>
    <?php include '../../../layouts/admin_menu_link.php'?>

    <!-- Create Button -->
    <div class="create-button">
        <button class="btn btn-primary" onclick="toggleForm()">
            <i class="fas fa-plus"></i>
            Create New
        </button>
    </div>

    <!-- Add User Form (Hidden by default) -->
    <div id="createForm" style="display: none;">
        <h2>Add New User</h2>
        <form method="POST" autocomplete="off">
            <label for="name">Name:</label>
            <input type="text" name="name" required autocomplete="off">
            
            <label for="email">Email:</label>
            <input type="email" name="email" required autocomplete="off">
            
            <label for="role">Role:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required autocomplete="new-password">
            
            <div class="form-buttons">
                <button type="submit" name="add_user" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save User
                </button>
                <button type="button" class="btn btn-secondary" onclick="toggleForm(); resetForm();">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- List of Users -->
    <h2>User List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $user['status'] ?? 'active'; ?>">
                            <?php echo ucfirst(htmlspecialchars($user['status'] ?? 'active')); ?>
                        </span>
                    </td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td><?php echo $user['updated_at']; ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?php echo $user['id']; ?>" class="edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="index.php?delete=<?php echo $user['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this user?')" 
                           class="remove">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php include '../../../layouts/admin_scripts.php'; ?>
    <?php include '../../../layouts/admin_footer_button.php'; ?>

    <style>
    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.85em;
        font-weight: 500;
    }

    .status-active {
        background-color: #e8f5e9;
        color: #2e7d32;
    }

    .status-inactive {
        background-color: #f5f5f5;
        color: #666;
    }

    .status-suspended {
        background-color: #ffebee;
        color: #c62828;
    }

    select[name="status"] {
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ddd;
        width: 100%;
        margin-bottom: 1rem;
    }
    </style>
</body>
</html>

