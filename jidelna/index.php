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

$pocet_v_kosiku = 0;
if (isset($_SESSION['kosik'])) {
    foreach ($_SESSION['kosik'] as $mnozstvi) {
        $pocet_v_kosiku += $mnozstvi;
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Jídelna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="hlavicka">
        <h1>Jídelna</h1>
        
        <div class="hlavicka-ovladani">
            <a href="kosik.php" class="btn-kosik">Košík (<?php echo $pocet_v_kosiku; ?>)</a>
            
            <div class="uzivatel-panel">
                <div class="uzivatel-sekce">
                    <span class="uzivatel-jmeno"><?php echo htmlspecialchars($_SESSION['jmeno']); ?></span>
                    <a href="logout.php" class="btn-cervena">Odhlásit</a>
                </div>
                
                <span class="oddelovac">|</span>
                
                <div class="uzivatel-sekce">
                    <span class="uzivatel-kredit"><?php echo number_format($_SESSION['kredit'], 2, ',', ' '); ?> Kč</span>
                    <a href="nabit_kredit.php" class="btn-zelena">+ Nabít kredit</a>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['pridano'])): ?>
        <p style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Jídlo bylo úspěšně přidáno do košíku!</p>
    <?php endif; ?>

    <?php if (isset($_GET['nabito'])): ?>
        <p style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">Kredit byl úspěšně navýšen!</p>
    <?php endif; ?>

    <?php foreach ($kategorie as $kat): ?>
        <?php if (isset($jidla_podle_kategorie[$kat['id']])): ?>
            <div class="kategorie-sekce">
                <h2><?php echo htmlspecialchars($kat['nazev']); ?></h2>
                
                <div class="kategorie-kontejner">
                    <?php foreach ($jidla_podle_kategorie[$kat['id']] as $jidlo): ?>
                        
                        <?php 
                        $obrazek_url = !empty($jidlo['obrazek']) 
                            ? htmlspecialchars($jidlo['obrazek']) 
                            : "https://placehold.co/400x300/e0e0e0/170C79?text=Bez+fotky";
                        ?>

                        <div class="karta">
                            <img src="<?php echo $obrazek_url; ?>" alt="Obrázek jídla" class="karta-img">
                            
                            <div class="karta-obsah">
                                <div class="karta-nazev"><?php echo htmlspecialchars($jidlo['nazev']); ?></div>
                                <div class="karta-cena"><?php echo number_format($jidlo['cena'], 2, ',', ' '); ?> Kč</div>
                                <div class="karta-popis"><?php echo htmlspecialchars($jidlo['popis']); ?></div>
                                
                                <div class="karta-tagy">
                                    <?php if (!empty($jidlo['alergeny'])): ?>
                                        <span class="tag">Alergeny: <?php echo htmlspecialchars($jidlo['alergeny']); ?></span>
                                    <?php endif; ?>
                                    <span class="tag"><?php echo $jidlo['kalorie']; ?> kcal</span>
                                    <span class="tag">B: <?php echo $jidlo['bilkoviny']; ?>g</span>
                                    <span class="tag">S: <?php echo $jidlo['sacharidy']; ?>g</span>
                                    <span class="tag">T: <?php echo $jidlo['tuky']; ?>g</span>
                                </div>

                                <form action="pridat_do_kosiku.php" method="POST" class="karta-akce">
                                    <input type="hidden" name="jidlo_id" value="<?php echo $jidlo['id']; ?>">
                                    <input type="number" name="mnozstvi" value="1" min="1">
                                    <button type="submit">Přidat</button>
                                </form>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</body>
</html>