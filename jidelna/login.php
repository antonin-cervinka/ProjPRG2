<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $heslo = $_POST['heslo'];

    if (!empty($email) && !empty($heslo)) {
        $stmt = $pdo->prepare("SELECT * FROM uzivatele WHERE email = ?");
        $stmt->execute([$email]);
        $uzivatel = $stmt->fetch();

        if ($uzivatel && password_verify($heslo, $uzivatel['heslo'])) {
            $_SESSION['uzivatel_id'] = $uzivatel['id'];
            $_SESSION['role'] = $uzivatel['role'];
            $_SESSION['jmeno'] = $uzivatel['jmeno'];
            $_SESSION['kredit'] = $uzivatel['kredit'];
            
            if ($uzivatel['role'] === 'admin') {
                header('Location: admin.php');
            } elseif ($uzivatel['role'] === 'kuchyne') {
                header('Location: kuchyne.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $chyba = "Nesprávný e-mail nebo heslo.";
        }
    } else {
        $chyba = "Prosím, vyplňte všechna pole.";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Přihlášení</title>
</head>
<body>
    <h1>Přihlášení do systému</h1>
    
    <?php if (isset($chyba)) echo "<p style='color: red;'>$chyba</p>"; ?>
    
    <form method="POST">
        <div>
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <br>
        <div>
            <label for="heslo">Heslo:</label>
            <input type="password" name="heslo" id="heslo" required>
        </div>
        <br>
        <button type="submit">Přihlásit</button>
    </form>
    <p>Nemáte účet? <a href="register.php">Zaregistrujte se</a>.</p>
</body>
</html>