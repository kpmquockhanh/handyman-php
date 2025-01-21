<?php
session_start();
require '../../../db.php';
include '../../../auth.php';
include_once '../../../s3.php';
require_once __DIR__ . '/../../../helpers.php';

// Fetch member details
if (isset($_GET['id'])) {
    if (!isset($conn)) exit();
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update member details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_member'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $skills = $_POST['skills'];
    $description = $_POST['description'];
    $image = $member['image'];
    $image_id = $member['image_id'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if (!isset($client)) exit();

        // Delete the old image if it exists
        if ($member['image']) {
            $fileDelete = $client->deleteFile([
                'FileId' => $member['image_id'],
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

    $stmt = $conn->prepare("UPDATE members SET name = :name, skills = :skills, description = :description, image = :image, image_id = :image_id WHERE id = :id");
    $stmt->execute([
        'name' => $name,
        'skills' => $skills,
        'description' => $description,
        'image' => $image,
        'image_id' => $image_id,
        'id' => $id,
    ]);
    header("Location: /admin/members");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
    <?php include '../../../layouts/admin_edit_styles.php'; ?>
</head>
<body>
<div class="page-header">
    <h1>Edit Member</h1>
</div>

<!-- Success Message -->
<?php if (isset($_POST['update_member'])): ?>
    <p class="success">Member updated successfully! Redirecting to admin page...</p>
<?php endif; ?>

<!-- Edit Member Form -->
<form method="POST" enctype="multipart/form-data" class="edit-form">
    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
    
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="skills">Skills:</label>
        <textarea id="skills" name="skills" required><?php echo htmlspecialchars($member['skills']); ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        <?php if ($member['image']): ?>
            <div class="preview-image">
                <p>Current Image:</p>
                <img src="<?= get_image_cdn($member['image']) ?>" alt="Member Image">
            </div>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($member['description']); ?></textarea>
    </div>
    
    <div class="button-group">
        <button type="submit" name="update_member" class="submit-button">Update Member</button>
        <a href="index.php" class="cancel-button">Cancel</a>
    </div>
</form>

<?php include '../../../layouts/admin_footer_button.php'; ?>
</body>
</html>
