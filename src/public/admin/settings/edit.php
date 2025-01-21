<?php
session_start();
require '../../../db.php';
include '../../../auth.php';
if (!isset($conn)) exit();

if (!isset($_GET['id'])) {
    header("Location: /admin/settings");
    exit();
}

$id = $_GET['id'];

// Update setting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_setting'])) {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $value = $_POST['value'];

    $stmt = $conn->prepare("UPDATE settings SET name = :name, code = :code, value = :value WHERE id = :id");
    $stmt->execute([
        'name' => $name,
        'code' => $code,
        'value' => $value,
        'id' => $id
    ]);

    header("Location: /admin/settings");
    exit();
}

// Fetch setting
$stmt = $conn->prepare("SELECT * FROM settings WHERE id = :id");
$stmt->execute(['id' => $id]);
$setting = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$setting) {
    header("Location: /admin/settings");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Setting</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
    <?php include '../../../layouts/admin_edit_styles.php'; ?>
</head>
<body>
<div class="page-header">
    <h1>Edit Setting</h1>
</div>

<form method="POST" class="edit-form">
    <div class="form-group">
        <label for="name">Setting Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($setting['name']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="code">Setting Code:</label>
        <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($setting['code']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="value">Value:</label>
        <textarea id="value" name="value" required><?php echo htmlspecialchars($setting['value']); ?></textarea>
    </div>
    
    <div class="button-group">
        <button type="submit" name="update_setting" class="submit-button">Update Setting</button>
        <a href="/admin/settings" class="cancel-button">Back to Settings</a>
    </div>
</form>

<?php include '../../../layouts/admin_footer_button.php'; ?>
</body>
</html> 