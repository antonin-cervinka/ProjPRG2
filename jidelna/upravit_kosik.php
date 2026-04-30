<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jidlo_id'], $_POST['akce'])) {
    $jidlo_id = (int)$_POST['jidlo_id'];
    $akce = $_POST['akce'];

    if (isset($_SESSION['kosik'][$jidlo_id])) {
        if ($akce === 'plus') {
            $_SESSION['kosik'][$jidlo_id]++;
        } elseif ($akce === 'minus') {
            $_SESSION['kosik'][$jidlo_id]--;
            
            // Pokud zákazník odkliká množství až na nulu (nebo méně), jídlo z košíku vyhodíme
            if ($_SESSION['kosik'][$jidlo_id] <= 0) {
                unset($_SESSION['kosik'][$jidlo_id]);
            }
        }
    }
}

// Po úpravě ho hned hodíme zpátky do košíku
header('Location: kosik.php');
exit;