<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['uzivatel_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategorie_id = (int)$_POST['kategorie_id'];
    $nazev = trim($_POST['nazev']);
    $cena = str_replace(',', '.', $_POST['cena']);
    $cena = (float)$cena;
    $popis = trim($_POST['popis']);
    $obrazek = trim($_POST['obrazek']);
    $alergeny = trim($_POST['alergeny']);
    $kalorie = (int)$_POST['kalorie'];
    
    $bilkoviny = (float)str_replace(',', '.', $_POST['bilkoviny']);
    $sacharidy = (float)str_replace(',', '.', $_POST['sacharidy']);
    $tuky = (float)str_replace(',', '.', $_POST['tuky']);

    if (!empty($nazev) && $cena > 0 && $kategorie_id > 0) {
        $stmt = $pdo->prepare("INSERT INTO jidla (kategorie_id, nazev, cena, popis, obrazek, alergeny, kalorie, bilkoviny, sacharidy, tuky) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$kategorie_id, $nazev, $cena, $popis, $obrazek, $alergeny, $kalorie, $bilkoviny, $sacharidy, $tuky]);
        
        header('Location: admin.php');
        exit;
    } else {
        $chyba = "Prosím, vyplňte minimálně kategorii, název a platnou cenu.";
    }
}

$stmt_kat = $pdo->query("SELECT id, nazev FROM kategorie");
$kategorie = $stmt_kat->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Přidat jídlo</title>
</head>
<body>
    <h1>Přidat nové jídlo</h1>
    <p><a href="admin.php">Zpět na administraci</a></p>

    <?php if (isset($chyba)) echo "<p style='color: red;'>$chyba</p>"; ?>

    <form method="POST">
        <div>
            <label for="kategorie_id">Kategorie:</label><br>
            <select name="kategorie_id" id="kategorie_id" required>
                <option value="">-- Vyberte kategorii --</option>
                <?php foreach ($kategorie as $k): ?>
                    <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nazev']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <div>
            <label for="nazev">Název jídla:</label><br>
            <input type="text" name="nazev" id="nazev" required>
        </div>
        <br>
        <div>
            <label for="cena">Cena (Kč):</label><br>
            <input type="number" step="0.01" name="cena" id="cena" required>
        </div>
        <br>
        <div>
            <label for="popis">Popis:</label><br>
            <textarea name="popis" id="popis" rows="3" cols="40"></textarea>
        </div>
        <br>
        <div>
            <label for="obrazek">Obrázek (název souboru nebo URL):</label><br>
            <input type="text" name="obrazek" id="obrazek">
        </div>
        <br>
        <div>
            <label for="alergeny">Alergeny (např. 1, 3, 7):</label><br>
            <input type="text" name="alergeny" id="alergeny">
        </div>
        <br>
        <div>
            <label for="kalorie">Kalorie (kcal):</label><br>
            <input type="number" name="kalorie" id="kalorie" value="0">
        </div>
        <br>
        <div>
            <label for="bilkoviny">Bílkoviny (g):</label><br>
            <input type="number" step="0.01" name="bilkoviny" id="bilkoviny" value="0">
        </div>
        <br>
        <div>
            <label for="sacharidy">Sacharidy (g):</label><br>
            <input type="number" step="0.01" name="sacharidy" id="sacharidy" value="0">
        </div>
        <br>
        <div>
            <label for="tuky">Tuky (g):</label><br>
            <input type="number" step="0.01" name="tuky" id="tuky" value="0">
        </div>
        <br>
        <button type="submit">Uložit jídlo</button>
    </form>
</body>
</html>