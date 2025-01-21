<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

// Fetch footer settings
$phone = get_setting('CONTACT_PHONE', '+61 402 195 476');
$email = get_setting('CONTACT_EMAIL', 'info@kpmquockhanh.site');
$working_hours_weekday = get_setting('WORKING_HOURS_WEEKDAY', 'Mon – Fri: 7am – 8pm (AEST)');
$working_hours_weekend = get_setting('WORKING_HOURS_WEEKEND', 'Sat – Sun: 8am – 4pm');
$copyright = get_setting('COPYRIGHT_TEXT', '© 2024 ICT726-Group 5. All rights reserved.');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handyman services</title>
    <?php include '../layouts/style.php'; ?>
</head>

<body>
<?php include '../layouts/header.php'; ?>
<main>
    <?php if (isset($content)) echo $content; ?>
</main>
<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-info">
            <nav class="footer-nav">
                <div class="footer-section">
                    <h4>About</h4>
                    <div class="footer-section-content">
                        <a href="/about-us.php">About us</a>
                        <a href="/services.php">Customer service</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Services</h4>
                    <div class="footer-section-content">
                        <?php
                        $services = get_services();
                        foreach ($services as $service) {
                            echo '<a href="#">' . htmlspecialchars($service['name']) . '</a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Locations</h4>
                    <div class="footer-section-content">
                        <?php
                        $locations = get_locations();
                        foreach ($locations as $location) {
                            echo '<a href="#">' . htmlspecialchars($location) . '</a>';
                        }
                        ?>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>CUSTOMER CENTRE</h4>
                    <div class="footer-section-content">
                        <h3><?php echo htmlspecialchars($phone); ?></h3>
                        <p class="mt"><?php echo htmlspecialchars($working_hours_weekday); ?></p>
                        <p class="mt"><?php echo htmlspecialchars($working_hours_weekend); ?></p>
                    </div>
                </div>
            </nav>
            <hr>
            <p class="footer-copyright mt"><?php echo htmlspecialchars($copyright); ?></p>
        </div>
    </div>
</footer>
<div class="loader-wrapper hide" id="loader">
    <i class="loader"></i>
</div>
</body>
<?php include '../layouts/scripts.php'; ?>

</html>