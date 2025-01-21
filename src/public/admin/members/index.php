<?php
session_start();
require '../../../db.php';
include '../../../auth.php';
include_once '../../../s3.php';
require_once __DIR__ . '/../../../helpers.php';
if (!isset($conn)) exit();
// Add a new member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_member'])) {
    $name = $_POST['name'];
    $skills = $_POST['skills'];
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


    $stmt = $conn->prepare("INSERT INTO members (name, skills, description, image, image_id) VALUES (:name, :skills, :description, :image, :image_id)");
    $stmt->execute([
        'name' => $name,
        'skills' => $skills,
        'description' => $description,
        'image' => $image,
        'image_id' => $image_id]);
    header("Location: /admin/members");
    exit();
}

// Delete a member
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    if (!isset($client)) exit();
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($member && $member['image']) {
        $fileDelete = $client->deleteFile([
            'FileId' => $member['image_id'],
            'FileName' => $member['image'],
        ]);
    }

    $stmt = $conn->prepare("DELETE FROM members WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: /admin/members");
    exit();
}

// Fetch all members
$stmt = $conn->query("SELECT * FROM members");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Members</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
</head>
<body>
<h1>Manage Members</h1>
<?php include '../../../layouts/admin_menu_link.php' ?>

<!-- Create Button -->
<div class="create-button">
        <button class="btn btn-primary" onclick="toggleForm()">
            <i class="fas fa-plus"></i>
            Create New
        </button>
    </div>

<!-- Add Member Form (Hidden by default) -->
<div id="createForm" style="display: none;">
    <h2>Add New Member</h2>
    <form method="POST" enctype="multipart/form-data" autocomplete="off">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required autocomplete="off">
        
        <label for="skills">Skills:</label>
        <textarea id="skills" name="skills" required></textarea>
        
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        
        <div class="form-buttons">
            <button type="submit" name="add_member" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Member
            </button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm(); resetForm();">
                <i class="fas fa-times"></i> Cancel
            </button>
        </div>
    </form>
</div>

<!-- List of Members -->
<h2>Member List</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Skills</th>
        <th>Image</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($members as $member): ?>
        <tr>
            <td><?php echo $member['id']; ?></td>
            <td><?php echo htmlspecialchars($member['name']); ?></td>
            <td><?php echo htmlspecialchars($member['skills']); ?></td>
            <td>
                <?php if ($member['image']): ?>
                    <img src="<?= get_image_cdn($member['image']) ?>" alt="Member Image" width="100">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($member['description']); ?></td>
            <td class="actions">
                <a href="edit.php?id=<?= $member['id']; ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
                <a href="index.php?delete=<?= $member['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this member?')" class="remove"><i class="fas fa-remove"></i>Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include '../../../layouts/admin_scripts.php'; ?>
<?php include '../../../layouts/admin_footer_button.php'; ?>
</script>
</body>
</html>
