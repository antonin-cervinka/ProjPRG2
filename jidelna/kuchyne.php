<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['uzivatel_id']) || $_SESSION['role'] !== 'kuchyne') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['objednavka_id'], $_POST['novy_stav'])) {
    $objednavka_id = (int)$_POST['objednavka_id'];
    $novy_stav = $_POST['novy_stav'];
    
    $povolenestavy = ['prijato', 'v_priprave', 'k_vyzvednuti', 'vydano'];
    
    if (in_array($novy_stav, $povolenestavy)) {
        $stmt_update = $pdo->prepare("UPDATE objednavky SET stav = ? WHERE id = ?");
        $stmt_update->execute([$novy_stav, $objednavka_id]);
        
        header('Location: kuchyne.php');
        exit;
    }
}

$stmt_objednavky = $pdo->query("
    SELECT o.id, o.stav, o.vytvoreno, u.jmeno 
    FROM objednavky o
    JOIN uzivatele u ON o.uzivatel_id = u.id
    WHERE o.stav != 'vydano'
    ORDER BY o.vytvoreno ASC
");
$objednavky = $stmt_objednavky->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Rozhraní pro kuchyni</title>
</head>
<body>
    <h1>Objednávky pro kuchyni</h1>
    <p>Přihlášen jako: <strong><?php echo htmlspecialchars($_SESSION['jmeno']); ?></strong> | <a href="logout.php">Odhlásit se</a></p>

    <?php if (empty($objednavky)): ?>
        <p>Aktuálně nejsou žádné nevyřízené objednávky.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>Čas objednávky</th>
                <th>Zákazník</th>
                <th>Položky</th>
                <th>Aktuální stav</th>
                <th>Akce</th>
            </tr>
            <?php foreach ($objednavky as $obj): ?>
                <tr>
                    <td><?php echo date('H:i', strtotime($obj['vytvoreno'])); ?></td>
                    <td><?php echo htmlspecialchars($obj['jmeno']); ?></td>
                    <td>
                        <ul>
                            <?php
                            $stmt_polozky = $pdo->prepare("
                                SELECT p.mnozstvi, j.nazev 
                                FROM polozky_objednavky p
                                JOIN jidla j ON p.jidlo_id = j.id
                                WHERE p.objednavka_id = ?
                            ");
                            $stmt_polozky->execute([$obj['id']]);
                            $polozky = $stmt_polozky->fetchAll();
                            
                            foreach ($polozky as $polozka) {
                                echo "<li>" . $polozka['mnozstvi'] . "x " . htmlspecialchars($polozka['nazev']) . "</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <?php 
                        $stavy_cesky = [
                            'prijato' => 'Přijato',
                            'v_priprave' => 'V přípravě',
                            'k_vyzvednuti' => 'K vyzvednutí'
                        ];
                        echo $stavy_cesky[$obj['stav']]; 
                        ?>
                    </td>
                    <td>
                        <form method="POST" style="margin: 0;">
                            <input type="hidden" name="objednavka_id" value="<?php echo $obj['id']; ?>">
                            <select name="novy_stav">
                                <option value="prijato" <?php if($obj['stav'] == 'prijato') echo 'selected'; ?>>Přijato</option>
                                <option value="v_priprave" <?php if($obj['stav'] == 'v_priprave') echo 'selected'; ?>>V přípravě</option>
                                <option value="k_vyzvednuti" <?php if($obj['stav'] == 'k_vyzvednuti') echo 'selected'; ?>>K vyzvednutí</option>
                                <option value="vydano">Vydáno (Dokončit)</option>
                            </select>
                            <button type="submit">Změnit stav</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>