<?php
session_start();

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Objednávka přijata</title>
</head>
<body>
    <h1 style="color: green;">Objednávka byla úspěšně odeslána!</h1>
    
    <p>Děkujeme za vaši objednávku. Kuchyně ji právě přijala do systému a brzy se pustí do přípravy.</p>
    
    <p>Váš aktuální zůstatek kreditu po odečtení platby je: <strong><?php echo number_format($_SESSION['kredit'], 2, ',', ' '); ?> Kč</strong></p>
    
    <br>
    <p><a href="index.php">Zpět na hlavní nabídku</a></p>
</body>
</html>