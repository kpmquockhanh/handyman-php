<?php
$count = [
    'members' => 0,
    'services' => 0,
    'users' => 0,
    'settings' => 0,
    'messages' => 0
];

if (isset($conn)) {
    // Using a single connection for all queries
    try {
        // Prepare all count queries
        $queries = [
            'members' => "SELECT COUNT(*) FROM members",
            'services' => "SELECT COUNT(*) FROM services",
            'users' => "SELECT COUNT(*) FROM users",
            'settings' => "SELECT COUNT(*) FROM settings",
            'messages' => "SELECT COUNT(*) FROM messages"
        ];

        // Execute each query and store the result
        foreach ($queries as $key => $query) {
            $stmt = $conn->query($query);
            $count[$key] = $stmt->fetchColumn();
        }
    } catch (PDOException $e) {
        error_log("Error fetching counts: " . $e->getMessage());
    }
}
?>

<div class="menu">
    <a href="/admin/members">Members (<?= $count['members'] ?>)</a>
    <a href="/admin/users">Users (<?= $count['users'] ?>)</a>
    <a href="/admin/services">Services (<?= $count['services'] ?>)</a>
    <a href="/admin/settings">Settings (<?= $count['settings'] ?>)</a>
    <a href="/admin/messages">Messages (<?= $count['messages'] ?>)</a>
</div>