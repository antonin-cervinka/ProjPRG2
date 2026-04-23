<?php
session_start();

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jidlo_id = (int)$_POST['jidlo_id'];
    $mnozstvi = (int)$_POST['mnozstvi'];

    if ($jidlo_id > 0 && $mnozstvi > 0) {
        if (!isset($_SESSION['kosik'])) {
            $_SESSION['kosik'] = [];
        }

        if (isset($_SESSION['kosik'][$jidlo_id])) {
            $_SESSION['kosik'][$jidlo_id] += $mnozstvi;
        } else {
            $_SESSION['kosik'][$jidlo_id] = $mnozstvi;
        }
    }
}

header('Location: index.php');
exit;