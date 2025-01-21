<!-- Header -->
<header class="header">
    <div class="container flex gap-2 justify-around align-items-center">
        <div class="logo">Handyman</div>
        <nav class="nav">
            <a href="/">Home</a>
            <a href="/about-us.php">About us</a>
            <a href="/services.php">Services</a>
            <a href="/contact-us.php">Contact</a>
        </nav>
        <div class="flex gap-1 header-actions">
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a class="btn btn-primary sm" href="/auth/login.php">Login</a>
                <a class="btn btn-secondary sm" href="/auth/login.php">Register</a>
            <?php } else { ?>
                <div class="user-info">
                    <div>Hi, <?php echo $_SESSION['user_name']; ?> - <?php echo $_SESSION['role']; ?></div>
                    <?php if ($_SESSION['role'] === 'admin') { ?>
                        <a class="btn btn-secondary sm" href="/admin" target="_blank">Admin</a>
                    <?php } ?>
                    <a class="btn btn-primary sm" href="/auth/logout.php">Logout</a>
                </div>
            <?php } ?>
        </div>
    </div>
</header>