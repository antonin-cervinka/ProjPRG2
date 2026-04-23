<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jmeno = trim($_POST['jmeno']);
    $email = trim($_POST['email']);
    $heslo = $_POST['heslo'];

    if (!empty($jmeno) && !empty($email) && !empty($heslo)) {
        $hesloHash = password_hash($heslo, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO uzivatele (jmeno, email, heslo) VALUES (?, ?, ?)");
            $stmt->execute([$jmeno, $email, $hesloHash]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $chyba = "Tento email je již zaregistrovaný.";
        }
    } else {
        $chyba = "Prosím, vyplňte všechna pole.";
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Registrace</title>
</head>
<body>
    <h1>Registrace zákazníka</h1>
    
    <?php if (isset($chyba)) echo "<p style='color: red;'>$chyba</p>"; ?>
    
    <form method="POST">
        <div>
            <label for="jmeno">Jméno a příjmení:</label>
            <input type="text" name="jmeno" id="jmeno" required>
        </div>
        <br>
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
        <button type="submit">Zaregistrovat</button>
    </form>
</body>
</html>