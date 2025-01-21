<?php
ob_start();
include '../pages/services.php';
$content = ob_get_clean(); 

include '../layouts/base.php';

