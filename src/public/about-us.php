<?php
ob_start();
include '../pages/about-us.php';
$content = ob_get_clean(); // Get the buffered content

include '../layouts/base.php';

