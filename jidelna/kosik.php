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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Košík</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Váš nákupní košík</h1>
    <p><a href="index.php">Zpět na menu</a></p>

    <?php if (empty($polozky)): ?>
        <p>Váš košík je zatím prázdný.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table>
                <tr>
                    <th>Jídlo</th>
                    <th>Cena za kus</th>
                    <th>Množství</th>
                    <th>Cena celkem</th>
                    <th>Akce</th>
                </tr>
                <?php foreach ($polozky as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['nazev']); ?></td>
                        <td><?php echo number_format($p['cena'], 2, ',', ' '); ?> Kč</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <form action="upravit_kosik.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="jidlo_id" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="akce" value="minus">
                                    <button type="submit" style="padding: 4px 10px; background-color: #6c757d;">-</button>
                                </form>

                                <span style="font-weight: bold; font-size: 1.1rem; min-width: 20px; text-align: center;"><?php echo $p['mnozstvi']; ?></span>

                                <form action="upravit_kosik.php" method="POST" style="margin: 0;">
                                    <input type="hidden" name="jidlo_id" value="<?php echo $p['id']; ?>">
                                    <input type="hidden" name="akce" value="plus">
                                    <button type="submit" style="padding: 4px 10px; background-color: var(--primarni-barva);">+</button>
                                </form>
                            </div>
                        </td>
                        <td><?php echo number_format($p['cena_celkem'], 2, ',', ' '); ?> Kč</td>
                        <td>
                            <form action="odstranit_z_kosiku.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="jidlo_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" style="background-color: #dc3545; padding: 6px 12px; font-size: 0.85rem;">Odebrat</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div style="margin-top: 20px;">
            <h3>Celková cena k úhradě: <?php echo number_format($celkova_cena, 2, ',', ' '); ?> Kč</h3>
            <p>Váš aktuální kredit: <strong><?php echo number_format($_SESSION['kredit'], 2, ',', ' '); ?> Kč</strong></p>

            <?php if ($_SESSION['kredit'] >= $celkova_cena): ?>
                <form action="zpracovat_objednavku.php" method="POST" style="margin-top: 15px;">
                    <button type="submit" style="background-color: #27ae60; font-size: 1.1rem; padding: 15px 25px;">
                        Závazně objednat a zaplatit z kreditu
                    </button>
                </form>
            <?php else: ?>
                <p style="color: red; font-weight: bold; margin-top: 15px;">Nemáte dostatek kreditu pro dokončení objednávky!</p>
            <?php endif; ?>
            
            <br>
            <form action="vysypat_kosik.php" method="POST">
                <button type="submit" style="background-color: #6c757d;">Vysypat celý košík</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>