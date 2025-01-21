<?php
session_start();
require '../../../db.php';
include '../../../auth.php';
include_once '../../../s3.php';
require_once __DIR__ . '/../../../helpers.php';
// Fetch service details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!isset($conn)) exit();
    $stmt = $conn->prepare("SELECT * FROM services WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update service details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $service['image']; // Keep the existing image by default
    $image_id = $service['image_id'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if (!isset($client)) exit();

        // Delete the old image if it exists
        if ($service['image']) {
            $fileDelete = $client->deleteFile([
                'FileId' => $image_id,
                'FileName' => $image,
            ]);
        }

        $timestamp = date("Ymd_His");
        $imageName = basename($timestamp . '_' . $_FILES['image']['name']);
        $file = $client->upload([
            'BucketName' => 'ict726',
            'FileName' => $imageName,
            'Body' => fopen($_FILES['image']['tmp_name'], 'r')
        ]);
        $image = $file->getFileName();
        $image_id = $file->getFileId();
    }
    // Update service
    $stmt = $conn->prepare("UPDATE services SET name = :name, description = :description, image = :image, image_id = :image_id WHERE id = :id");
    $stmt->execute(['name' => $name, 'description' => $description, 'image' => $image, 'image_id' => $image_id, 'id' => $id]);
    header('Location: /admin/services');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
    <?php include '../../../layouts/admin_edit_styles.php'; ?>
</head>
<body>
<div class="page-header">
    <h1>Edit Service</h1>
</div>

<form method="POST" enctype="multipart/form-data" class="edit-form">
    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
    
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($service['name']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($service['description']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        <?php if ($service['image']): ?>
            <div class="preview-image">
                <p>Current Image:</p>
                <img src="<?= get_image_cdn($service['image']) ?>" alt="Service Image">
            </div>
        <?php endif; ?>
    </div>
    
    <div class="button-group">
        <button type="submit" name="update_service" class="submit-button">Update Service</button>
        <a href="index.php" class="cancel-button">Back to Manage Services</a>
    </div>
</form>

<?php include '../../../layouts/admin_footer_button.php'; ?>
</body>
</html>