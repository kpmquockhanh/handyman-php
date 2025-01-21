<?php
session_start();
require '../../../db.php';
include '../../../auth.php';
if (!isset($conn)) exit();

// Delete a message
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: /admin/messages");
    exit();
}

// Fetch all messages, ordered by newest first
$stmt = $conn->query("SELECT * FROM messages ORDER BY created_date DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Messages</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
    <style>
        .message-content {
            max-width: 300px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .date-column {
            white-space: nowrap;
        }
    </style>
</head>
<body>
<h1>Manage Messages</h1>
<?php include '../../../layouts/admin_menu_link.php' ?>

<!-- List of Messages -->
<h2>Messages List</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($messages as $message): ?>
        <tr>
            <td><?php echo $message['id']; ?></td>
            <td><?php echo htmlspecialchars($message['name']); ?></td>
            <td><a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a></td>
            <td class="message-content"><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
            <td class="date-column"><?php echo date('Y-m-d H:i', strtotime($message['created_date'])); ?></td>
            <td class="actions">
                <a href="view.php?id=<?= $message['id']; ?>" class="view"><i class="fas fa-eye"></i> View</a>
                <a href="index.php?delete=<?= $message['id']; ?>"
                   onclick="return confirm('Are you sure you want to delete this message?')" class="remove"><i class="fas fa-remove"></i>Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include '../../../layouts/admin_footer_button.php'; ?>
</body>
</html> 