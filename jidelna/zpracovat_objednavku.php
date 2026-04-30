<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}

if (empty($_SESSION['kosik'])) {
    header('Location: kosik.php');
    exit;
}

$uzivatel_id = $_SESSION['uzivatel_id'];
$kosik = $_SESSION['kosik'];
$celkova_cena = 0;
$polozky_k_ulozeni = [];

$stmt_cena = $pdo->prepare("SELECT id, cena FROM jidla WHERE id = ?");
foreach ($kosik as $jidlo_id => $mnozstvi) {
    $stmt_cena->execute([$jidlo_id]);
    $jidlo = $stmt_cena->fetch();
    
    if ($jidlo) {
        $cena_polozky = $jidlo['cena'] * $mnozstvi;
        $celkova_cena += $cena_polozky;
        $polozky_k_ulozeni[] = [
            'jidlo_id' => $jidlo_id,
            'mnozstvi' => $mnozstvi,
            'cena_za_kus' => $jidlo['cena']
        ];
    }
}

if ($celkova_cena == 0) {
    header('Location: kosik.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt_kredit = $pdo->prepare("SELECT kredit FROM uzivatele WHERE id = ?");
    $stmt_kredit->execute([$uzivatel_id]);
    $uzivatel = $stmt_kredit->fetch();

    if ($uzivatel['kredit'] < $celkova_cena) {
        $pdo->rollBack();
        header('Location: kosik.php?chyba=kredit');
        exit;
    }

    $novy_kredit = $uzivatel['kredit'] - $celkova_cena;
    $stmt_update_kredit = $pdo->prepare("UPDATE uzivatele SET kredit = ? WHERE id = ?");
    $stmt_update_kredit->execute([$novy_kredit, $uzivatel_id]);

    $stmt_obj = $pdo->prepare("INSERT INTO objednavky (uzivatel_id, stav, celkova_cena) VALUES (?, 'prijato', ?)");
    $stmt_obj->execute([$uzivatel_id, $celkova_cena]);
    
    $objednavka_id = $pdo->lastInsertId();

    $stmt_polozka = $pdo->prepare("INSERT INTO polozky_objednavky (objednavka_id, jidlo_id, mnozstvi, cena_za_kus) VALUES (?, ?, ?, ?)");
    foreach ($polozky_k_ulozeni as $p) {
        $stmt_polozka->execute([$objednavka_id, $p['jidlo_id'], $p['mnozstvi'], $p['cena_za_kus']]);
    }

    $pdo->commit();

    $_SESSION['kredit'] = $novy_kredit;
    unset($_SESSION['kosik']);

    header('Location: uspech.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Chyba při zpracování: " . $e->getMessage());
}