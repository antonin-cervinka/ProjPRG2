<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['castka'])) {
    $castka = (float)$_POST['castka'];
    
    if ($castka > 0) {
        $stmt = $pdo->prepare("UPDATE uzivatele SET kredit = kredit + ? WHERE id = ?");
        $stmt->execute([$castka, $_SESSION['uzivatel_id']]);
        
        $_SESSION['kredit'] += $castka;
        
        header('Location: index.php?nabito=1');
        exit;
    } else {
        $chyba = "Částka musí být větší než 0.";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nabít kredit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Nabít kredit</h1>
    <p><a href="index.php">Zpět na menu</a></p>

    <p>Aktuální zůstatek: <strong><?php echo number_format($_SESSION['kredit'], 2, ',', ' '); ?> Kč</strong></p>

    <?php if (isset($chyba)) echo "<p style='color: #721c24; background: #f8d7da; padding: 10px; border-radius: 5px;'>$chyba</p>"; ?>

    <form method="POST">
        <label for="castka">Zadejte částku k nabití (Kč):</label><br>
        <input type="number" step="1" min="1" name="castka" id="castka" required>
        <button type="submit" style="background-color: #27ae60;">Nabít účet</button>
    </form>
</body>
</html>