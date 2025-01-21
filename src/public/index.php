<?php
ob_start();
include '../pages/home.php';
$content = ob_get_clean(); // Get the buffered content

include '../layouts/base.php';

