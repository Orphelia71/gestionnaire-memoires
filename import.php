<?php
include 'db.php';

if (isset($_POST['import_submit'])) {
    if ($_FILES['file_csv']['error'] == 0) {
        $filename = $_FILES['file_csv']['tmp_name'];
        $file = fopen($filename, "r");
        
        fgetcsv($file, 1000, ";");

        while (($row = fgetcsv($file, 1000, ";")) !== FALSE) {
            $sql = "INSERT INTO memoires (titre, filiere, date_soutenance, centre, professeur, nom_fichier, statut, id_auteur) 
                    VALUES (?, ?, ?, ?, ?, ?, 'PUBLIE', 1)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]]);
        }
        fclose($file);
        echo "<script>alert('Importation réussie !'); window.location.href='index.php';</script>";
    } else {
        echo "Erreur lors du chargement du fichier.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Import de masse (ST4)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background: #f4f4f4;}
        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Espace Informaticien / DE (ST4)</h2>
    <p>Uploadez un fichier <strong>.csv</strong> (généré depuis Excel) contenant la liste des mémoires.</p>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file_csv" accept=".csv" required><br><br>
        <button type="submit" name="import_submit" style="background:#007BFF; color:white; padding:10px; border:none; border-radius:5px; width:100%; cursor:pointer;">Importer la liste</button>
    </form>
</div>

</body>
</html>