<?php
include 'db.php';
session_start();

$erreur = "";

if (isset($_POST['connexion'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $password === $user['mot_de_passe']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nom_utilisateur'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'DE_INFO') {
            header('Location: dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $erreur = "Identifiant ou mot de passe incorrect !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background: #007BFF; color: white; border: none; font-weight: bold; cursor: pointer; }
        .error { color: red; font-size: 14px; text-align: center; }
    </style>
</head>
<body>

<div class="login-box">
    <h2 style="text-align:center;">Connexion</h2>
    
    <?php if(!empty($erreur)): ?>
        <p class="error"><?= $erreur ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" required placeholder="ex: admin_de ou etudiant1">
        
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        
        <button type="submit" name="connexion">Se connecter</button>
    </form>
</div>

</body>
</html>