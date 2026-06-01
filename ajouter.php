<?php
include 'db.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'DE_INFO') {
    header('Location: login.php');
    exit();
}

$message = "";

if (isset($_POST['submit_memoire'])) {
    $titre = $_POST['titre'];
    $filiere = $_POST['filiere'];
    $date_soutenance = $_POST['date_soutenance'];
    $centre = $_POST['centre'];
    $professeur = $_POST['professeur'];
    $resume = $_POST['resume']; 

    if ($_FILES['fichier_memoire']['error'] == 0) {
        $nom_fichier = time() . '_' . basename($_FILES['fichier_memoire']['name']);
        $destination = 'uploads/' . $nom_fichier;
        
        if (move_uploaded_file($_FILES['fichier_memoire']['tmp_name'], $destination)) {
            
            $sql = "INSERT INTO memoires (titre, filiere, date_soutenance, centre, professeur, nom_fichier, resume, statut, id_auteur) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'PUBLIE', ?)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([$titre, $filiere, $date_soutenance, $centre, $professeur, $nom_fichier, $resume, $_SESSION['user_id']]);
            
            $message = "<p style='color:green;'>🎉 Mémoire et résumé ajoutés avec succès !</p>";
        } else {
            $message = "<p style='color:red;'>Erreur lors du déplacement du fichier.</p>";
        }
    } else {
        $message = "<p style='color:red;'>Veuillez sélectionner un fichier valide.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un mémoire</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 40px; }
        .form-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        input, select, button, textarea { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { background: #28a745; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>➕ Ajouter un mémoire à l'unité</h2>
    <p><a href="dashboard.php">⬅ Retour au tableau de bord</a></p>
    
    <?= $message ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>Titre du mémoire :</label>
        <input type="text" name="titre" required placeholder="Ex: Impact de la blockchain...">

        <label>Filière :</label>
        <input type="text" name="filiere" required placeholder="Ex: Informatique">

        <label>Date de soutenance :</label>
        <input type="date" name="date_soutenance" required>

        <label>Centre / Établissement :</label>
        <input type="text" name="centre" required placeholder="Ex: Campus A">

        <label>Professeur encadrant :</label>
        <input type="text" name="professeur" required placeholder="Ex: Dr. Diallo">

        <label>Résumé du mémoire :</label>
        <textarea name="resume" rows="6" required placeholder="Écrivez ou collez le résumé ici..."></textarea>

        <label>Fichier du mémoire (PDF ou Word) :</label>
        <input type="file" name="fichier_memoire" accept=".pdf,.doc,.docx" required>

        <button type="submit" name="submit_memoire">Publier le mémoire</button>
    </form>
</div>

</body>
</html>