<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['uzivatel_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zmenit_stav_jidla'])) {
    $jidlo_id = (int)$_POST['jidlo_id'];
    $novy_stav = (int)$_POST['novy_stav'];
    
    $stmt_update = $pdo->prepare("UPDATE jidla SET aktivni = ? WHERE id = ?");
    $stmt_update->execute([$novy_stav, $jidlo_id]);
    
    header('Location: admin.php');
    exit;
}

$stmt_obrat = $pdo->query("SELECT SUM(celkova_cena) as celkem FROM objednavky");
$obrat = $stmt_obrat->fetch()['celkem'] ?? 0;

$stmt_top_jidla = $pdo->query("
    SELECT j.nazev, SUM(p.mnozstvi) as prodano 
    FROM polozky_objednavky p
    JOIN jidla j ON p.jidlo_id = j.id
    GROUP BY j.id
    ORDER BY prodano DESC
    LIMIT 5
");
$top_jidla = $stmt_top_jidla->fetchAll();

$stmt_uzivatele = $pdo->query("SELECT jmeno, email, kredit FROM uzivatele WHERE role = 'zakaznik' ORDER BY kredit DESC");
$uzivatele = $stmt_uzivatele->fetchAll();

$stmt_vsechna_jidla = $pdo->query("
    SELECT j.id, j.nazev, j.cena, j.aktivni, k.nazev as kategorie 
    FROM jidla j
    JOIN kategorie k ON j.kategorie_id = k.id
    ORDER BY k.id, j.nazev
");
$vsechna_jidla = $stmt_vsechna_jidla->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Administrátorský panel</h1>
    <p>Přihlášen jako: <strong><?php echo htmlspecialchars($_SESSION['jmeno']); ?></strong> | <a href="logout.php">Odhlásit se</a></p>

    <h2>Statistiky</h2>
    <p>Celkový obrat systému: <strong><?php echo number_format($obrat, 2, ',', ' '); ?> Kč</strong></p>

    <div class="admin-statistiky-grid">
        <div class="stat-box">
            <h3>Nejprodávanější jídla (Top 5)</h3>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Jídlo</th>
                    <th>Prodaných porcí</th>
                </tr>
                <?php foreach ($top_jidla as $tj): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tj['nazev']); ?></td>
                        <td><?php echo $tj['prodano']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div class="stat-box">
            <h3>Zákazníci a jejich kredit</h3>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Jméno</th>
                    <th>E-mail</th>
                    <th>Zůstatek</th>
                </tr>
                <?php foreach ($uzivatele as $uz): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($uz['jmeno']); ?></td>
                        <td><?php echo htmlspecialchars($uz['email']); ?></td>
                        <td><?php echo number_format($uz['kredit'], 2, ',', ' '); ?> Kč</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <h2>Správa nabídky</h2>
    <p><a href="pridat_jidlo.php">Přidat nové jídlo do systému</a> | <a href="sprava_kategorii.php">Spravovat kategorie</a></p>
    
    <div style="overflow-x: auto;">
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Kategorie</th>
                <th>Název jídla</th>
                <th>Cena</th>
                <th>Stav</th>
                <th>Akce</th>
            </tr>
            <?php foreach ($vsechna_jidla as $j): ?>
                <tr>
                    <td><?php echo htmlspecialchars($j['kategorie']); ?></td>
                    <td><?php echo htmlspecialchars($j['nazev']); ?></td>
                    <td><?php echo number_format($j['cena'], 2, ',', ' '); ?> Kč</td>
                    <td style="color: <?php echo $j['aktivni'] ? 'green' : 'red'; ?>;">
                        <?php echo $j['aktivni'] ? 'Aktivní' : 'Skryto'; ?>
                    </td>
                    <td>
                        <form method="POST" style="margin: 0;">
                            <input type="hidden" name="zmenit_stav_jidla" value="1">
                            <input type="hidden" name="jidlo_id" value="<?php echo $j['id']; ?>">
                            <input type="hidden" name="novy_stav" value="<?php echo $j['aktivni'] ? '0' : '1'; ?>">
                            <button type="submit">
                                <?php echo $j['aktivni'] ? 'Skrýt jídlo' : 'Aktivovat jídlo'; ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>