<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}

$kosik = isset($_SESSION['kosik']) ? $_SESSION['kosik'] : [];
$polozky = [];
$celkova_cena = 0;

if (!empty($kosik)) {
    $stmt = $pdo->prepare("SELECT id, nazev, cena FROM jidla WHERE id = ?");
    
    foreach ($kosik as $jidlo_id => $mnozstvi) {
        $stmt->execute([$jidlo_id]);
        $jidlo = $stmt->fetch();
        
        if ($jidlo) {
            $cena_polozky = $jidlo['cena'] * $mnozstvi;
            $celkova_cena += $cena_polozky;
            
            $jidlo['mnozstvi'] = $mnozstvi;
            $jidlo['cena_celkem'] = $cena_polozky;
            $polozky[] = $jidlo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Košík</title>
</head>
<body>
    <h1>Váš nákupní košík</h1>
    <p><a href="index.php">Zpět na menu</a></p>

    <?php if (empty($polozky)): ?>
        <p>Váš košík je zatím prázdný.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Jídlo</th>
                <th>Cena za kus</th>
                <th>Množství</th>
                <th>Cena celkem</th>
            </tr>
            <?php foreach ($polozky as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['nazev']); ?></td>
                    <td><?php echo number_format($p['cena'], 2, ',', ' '); ?> Kč</td>
                    <td><?php echo $p['mnozstvi']; ?></td>
                    <td><?php echo number_format($p['cena_celkem'], 2, ',', ' '); ?> Kč</td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h3>Celková cena k úhradě: <?php echo number_format($celkova_cena, 2, ',', ' '); ?> Kč</h3>
        <p>Váš aktuální kredit: <strong><?php echo number_format($_SESSION['kredit'], 2, ',', ' '); ?> Kč</strong></p>

        <?php if ($_SESSION['kredit'] >= $celkova_cena): ?>
            <form action="zpracovat_objednavku.php" method="POST">
                <button type="submit" style="padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
                    Závazně objednat a zaplatit z kreditu
                </button>
            </form>
        <?php else: ?>
            <p style="color: red;"><strong>Nemáte dostatek kreditu pro dokončení objednávky!</strong></p>
        <?php endif; ?>
        
        <br>
        <form action="vysypat_kosik.php" method="POST">
            <button type="submit">Vysypat košík</button>
        </form>
    <?php endif; ?>
</body>
</html>