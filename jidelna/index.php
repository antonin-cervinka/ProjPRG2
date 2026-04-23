<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}

$stmt_kategorie = $pdo->query("SELECT * FROM kategorie");
$kategorie = $stmt_kategorie->fetchAll();

$stmt_jidla = $pdo->query("SELECT * FROM jidla WHERE aktivni = 1");
$jidla_vsechna = $stmt_jidla->fetchAll();

$jidla_podle_kategorie = [];
foreach ($jidla_vsechna as $j) {
    $jidla_podle_kategorie[$j['kategorie_id']][] = $j;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Menu - Jídelna</title>
</head>
<body>
    <h1>Vítejte, <?php echo htmlspecialchars($_SESSION['jmeno']); ?>!</h1>
    
    <p>Váš aktuální kredit: <strong><?php echo number_format($_SESSION['kredit'], 2, ',', ' '); ?> Kč</strong></p>
    <p>
        <a href="kosik.php">Přejít do košíku</a> | 
        <a href="logout.php">Odhlásit se</a>
    </p>

    <h2>Nabídka jídel</h2>

    <?php foreach ($kategorie as $kat): ?>
        <h3><?php echo htmlspecialchars($kat['nazev']); ?></h3>
        
        <?php if (isset($jidla_podle_kategorie[$kat['id']])): ?>
            <ul>
                <?php foreach ($jidla_podle_kategorie[$kat['id']] as $jidlo): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($jidlo['nazev']); ?></strong> 
                        - <?php echo number_format($jidlo['cena'], 2, ',', ' '); ?> Kč
                        <br>
                        <small><?php echo htmlspecialchars($jidlo['popis']); ?></small><br>
                        <small>Alergeny: <?php echo htmlspecialchars($jidlo['alergeny']); ?></small><br>
                        <small>Hodnoty: <?php echo $jidlo['kalorie']; ?> kcal, B: <?php echo $jidlo['bilkoviny']; ?>g, S: <?php echo $jidlo['sacharidy']; ?>g, T: <?php echo $jidlo['tuky']; ?>g</small><br>
                        
                        <form action="pridat_do_kosiku.php" method="POST" style="display:inline-block; margin-top:5px;">
                            <input type="hidden" name="jidlo_id" value="<?php echo $jidlo['id']; ?>">
                            <input type="number" name="mnozstvi" value="1" min="1" style="width: 50px;">
                            <button type="submit">Přidat do košíku</button>
                        </form>
                    </li>
                    <br>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>V této kategorii zatím nejsou žádná jídla.</p>
        <?php endif; ?>
    <?php endforeach; ?>
</body>
</html>