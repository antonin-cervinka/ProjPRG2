<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jidlo_id'])) {
    $jidlo_id = (int)$_POST['jidlo_id'];
    
    // Pokud je jídlo v košíku, odstraníme celý jeho záznam
    if (isset($_SESSION['kosik'][$jidlo_id])) {
        unset($_SESSION['kosik'][$jidlo_id]);
    }
}

// Okamžitý návrat zpět do košíku
header('Location: kosik.php');
exit;