<?php
session_start();
require '../../db.php';
include '../../auth.php';

// Fetch some basic statistics
$stats = [];

// Count total messages
$stmt = $conn->query("SELECT COUNT(*) FROM messages");
$stats['messages'] = $stmt->fetchColumn();

// Count total members
$stmt = $conn->query("SELECT COUNT(*) FROM members");
$stats['members'] = $stmt->fetchColumn();

// Count total services
$stmt = $conn->query("SELECT COUNT(*) FROM services");
$stats['services'] = $stmt->fetchColumn();

// Get recent messages
$stmt = $conn->query("SELECT * FROM messages ORDER BY created_date DESC LIMIT 5");
$recentMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include '../../layouts/admin_styles.php'; ?>
    <style>
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .stat-number {
            font-size: 2em;
            color: #4CAF50;
            font-weight: bold;
        }
        
        .quick-actions {
            margin: 30px 0;
        }
        
        .action-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .action-button:hover {
            background: #45a049;
        }
        
        .recent-messages {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        
        .message-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .message-item:last-child {
            border-bottom: none;
        }
        
        .welcome-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="welcome-section">
    <h1>Admin Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</p>
</div>

<!-- Statistics Cards -->
<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Total Messages</h3>
        <div class="stat-number"><?php echo $stats['messages']; ?></div>
    </div>
    <div class="stat-card">
        <h3>Total Members</h3>
        <div class="stat-number"><?php echo $stats['members']; ?></div>
    </div>
    <div class="stat-card">
        <h3>Total Services</h3>
        <div class="stat-number"><?php echo $stats['services']; ?></div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h2>Quick Actions</h2>
    <a href="/admin/messages" class="action-button"><i class="fas fa-envelope"></i> View Messages</a>
    <a href="/admin/members" class="action-button"><i class="fas fa-users"></i> Manage Members</a>
    <a href="/admin/services" class="action-button"><i class="fas fa-cogs"></i> Manage Services</a>
    <a href="/admin/settings" class="action-button"><i class="fas fa-gear"></i> Settings</a>
</div>

<!-- Recent Messages -->
<div class="recent-messages">
    <h2>Recent Messages</h2>
    <?php if ($recentMessages): ?>
        <?php foreach ($recentMessages as $message): ?>
            <div class="message-item">
                <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                <span style="color: #666; font-size: 0.9em;">
                    (<?php echo date('M d, Y', strtotime($message['created_date'])); ?>)
                </span>
                <p><?php echo htmlspecialchars(substr($message['message'], 0, 100)) . '...'; ?></p>
                <a href="/admin/messages/view.php?id=<?php echo $message['id']; ?>">Read more</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recent messages.</p>
    <?php endif; ?>
</div>

<!-- Menu Links -->
<?php include '../../layouts/admin_menu_link.php'?>

<!-- Logout Link -->
<div style="margin-top: 20px; text-align: right;">
    <a class="logout btn" href="/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

</body>
</html>
