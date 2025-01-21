<?php
session_start();
require '../../../db.php';
include '../../../auth.php';
include_once '../../../s3.php';
require_once __DIR__ . '/../../../helpers.php';
if (!isset($conn)) exit();
// Add a new service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = '';
    $image_id = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if (!isset($client)) exit();
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

    // Insert new service
    $stmt = $conn->prepare("INSERT INTO services (name, description, image, image_id) VALUES (:name, :description, :image, :image_id)");
    $stmt->execute(['name' => $name, 'description' => $description, 'image' => $image, 'image_id' => $image_id]);
    header('Location: /admin/services');
    exit();
}

// Delete a service
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (!isset($client)) exit();
    // Fetch the service to get the image path
    $stmt = $conn->prepare("SELECT image FROM services WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    // Delete the image file if it exists
    if ($service['image']) {
        $fileDelete = $client->deleteFile([
            'FileId' => $service['image_id'],
            'FileName' => $service['image'],
        ]);
    }

    // Delete the service from the database
    $stmt = $conn->prepare("DELETE FROM services WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header('Location: /admin/services');
    exit();
}

// Fetch all services
$stmt = $conn->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
</head>
<body>
<h1>Manage Services</h1>

<!-- Menu Links -->
<?php include '../../../layouts/admin_menu_link.php'?>

<!-- Create Button -->
<div class="create-button">
    <button class="btn btn-primary" onclick="toggleForm()">
        <i class="fas fa-plus"></i>
        Create New Service
    </button>
</div>

<!-- Add Service Form (Hidden by default) -->
<div id="createForm" style="display: none;">
    <h2>Add New Service</h2>
    <form method="POST" enctype="multipart/form-data" autocomplete="off">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required autocomplete="off">
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        
        <div class="form-buttons">
            <button type="submit" name="add_service" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Service
            </button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm(); resetForm();">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </form>
</div>

<!-- List of Services -->
<h2>Service List</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($services as $service): ?>
        <tr>
            <td><?php echo $service['id']; ?></td>
            <td><?php echo htmlspecialchars($service['name']); ?></td>
            <td><?php echo htmlspecialchars($service['description']); ?></td>
            <td>
                <?php if ($service['image']): ?>
                    <img src="<?= get_image_cdn($service['image']) ?>" alt="Service Image" width="100">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td class="actions">
                <a href="edit.php?id=<?= $service['id']; ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
                <a href="index.php?delete=<?= $service['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this service?')" class="remove"><i class="fas fa-remove"></i>Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include '../../../layouts/admin_scripts.php'; ?>
<?php include '../../../layouts/admin_footer_button.php'; ?>
</body>
</html>