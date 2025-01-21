<?php
session_start();

require '../../../db.php';
include '../../../auth.php';
if (!isset($conn)) exit();

// Add or update a setting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_setting'])) {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $value = $_POST['value'];

    // Check if setting already exists
    $stmt = $conn->prepare("SELECT id FROM settings WHERE code = :code");
    $stmt->execute(['code' => $code]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update existing setting
        $stmt = $conn->prepare("UPDATE settings SET name = :name, value = :value WHERE code = :code");
    } else {
        // Insert new setting
        $stmt = $conn->prepare("INSERT INTO settings (name, code, value) VALUES (:name, :code, :value)");
    }

    $stmt->execute([
        'name' => $name,
        'code' => $code,
        'value' => $value
    ]);
    
    header("Location: /admin/settings");
    exit();
}

// Fetch all settings
$stmt = $conn->query("SELECT * FROM settings");
$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Settings</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
</head>
<body>
<h1>Manage Settings</h1>
<?php include '../../../layouts/admin_menu_link.php' ?>

<!-- Create Button -->
<div class="create-button">
    <button class="btn btn-primary" onclick="toggleForm()">
        <i class="fas fa-plus"></i>
        Create New Setting
    </button>
</div>

<!-- Add Setting Form (Hidden by default) -->
<div id="createForm" style="display: none;">
    <h2>Add New Setting</h2>
    <form method="POST" autocomplete="off">
        <label for="name">Setting Name:</label>
        <input type="text" name="name" required>
        
        <label for="code">Setting Code:</label>
        <input type="text" name="code" required placeholder="e.g., SITE_TITLE">
        
        <label for="value">Value:</label>
        <textarea name="value" required></textarea>
        
        <div class="form-buttons">
            <button type="submit" name="save_setting" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Setting
            </button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm(); resetForm();">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </form>
</div>

<!-- List of Settings -->
<h2>Settings List</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Code</th>
        <th>Value</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($settings as $setting): ?>
        <tr>
            <td><?php echo $setting['id']; ?></td>
            <td><?php echo htmlspecialchars($setting['name']); ?></td>
            <td><?php echo htmlspecialchars($setting['code']); ?></td>
            <td><?php echo htmlspecialchars($setting['value']); ?></td>
            <td class="actions">
                <a href="edit.php?id=<?= $setting['id']; ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include '../../../layouts/admin_scripts.php'; ?>
<?php include '../../../layouts/admin_footer_button.php'; ?>
</body>
</html>
