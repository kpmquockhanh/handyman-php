<?php
session_start();
require '../../../db.php';
include '../../../auth.php';
if (!isset($conn)) exit();

if (!isset($_GET['id'])) {
    header("Location: /admin/messages");
    exit();
}

$id = $_GET['id'];

// Fetch message
$stmt = $conn->prepare("SELECT * FROM messages WHERE id = :id");
$stmt->execute(['id' => $id]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$message) {
    header("Location: /admin/messages");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Message</title>
    <?php include '../../../layouts/admin_styles.php'; ?>
    <style>
        .message-details {
            margin: 20px auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .message-content {
            /* white-space: pre-wrap; */
            word-wrap: break-word;
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .message-meta {
            color: #666;
            font-size: 0.9em;
        }
        .actions {
            margin-top: 20px;
        }
        .reply-button {
            background: #4CAF50;
            color: white !important;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<h1>View Message</h1>
<?php include '../../../layouts/admin_menu_link.php' ?>

<div class="message-details">
    <h2>Message from <?php echo htmlspecialchars($message['name']); ?></h2>
    
    <div class="message-meta">
        <p><strong>From:</strong> <?php echo htmlspecialchars($message['name']); ?> 
           (<a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a>)</p>
        <p><strong>Received:</strong> <?php echo date('Y-m-d H:i:s', strtotime($message['created_date'])); ?></p>
    </div>

    <div class="message-content">
        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
    </div>

    <div class="actions">
        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="reply-button"><i class="fas fa-reply"></i> Reply by Email</a>
        <a href="index.php?delete=<?= $message['id']; ?>" 
           onclick="return confirm('Are you sure you want to delete this message?')" 
           class="delete-button remove"><i class="fas fa-remove"></i>Delete Message</a>
    </div>
</div>

<?php include '../../../layouts/admin_footer_button.php'; ?>
</body>
</html> 