<?php
session_start();

if (isset($_SESSION['kosik'])) {
    unset($_SESSION['kosik']);
}

header('Location: kosik.php');
exit;