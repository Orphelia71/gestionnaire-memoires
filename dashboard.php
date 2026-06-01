<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'DE_INFO') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Direction des Études</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f9f9f9; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .action-box { border: 1px solid #e0e0e0; padding: 20px; border-radius: 6px; margin-bottom: 20px; background: #fff; }
        .navbar { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px;}
        .btn-logout { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="navbar">
        <h2>Bonjour, <?= htmlspecialchars($_SESSION['username']) ?> (Espace DE)</h2>
        <a href="logout.php" class="btn-logout">🚪 Déconnexion</a>
    </div>

    <div class="action-box">
        <h3>➕ Ajouter un mémoire à l'unité (ST3)</h3>
        <p>Utilisez ce formulaire pour uploader les fichiers PDF/Word d'un seul étudiant.</p>
        <a href="ajouter.php" style="display:inline-block; background:#28a745; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;">Ouvrir le formulaire</a>
    </div>

    <div class="action-box">
        <h3>📊 Importer une liste de mémoires en masse (ST4)</h3>
        <p>Uploadez votre fichier Excel enregistré au format .csv pour ajouter des dizaines de mémoires d'un coup.</p>
        <a href="import.php" style="display:inline-block; background:#007BFF; color:white; padding:10px 15px; text-decoration:none; border-radius:5px;">Aller à la page d'importation</a>
    </div>
    
    <p><a href="index.php">👁 Voir le site côté public (Moteur de recherche)</a></p>
</div>

</body>
</html>