<?php
include 'db.php';

if (isset($_POST['submit_commentaire'])) {
    $id_memoire = intval($_POST['id_memoire']);
    $id_utilisateur = intval($_POST['id_utilisateur']);
    $texte = trim($_POST['texte_commentaire']);

    if (!empty($texte)) {
        $sql = "INSERT INTO commentaires (id_memoire, id_utilisateur, texte) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_memoire, $id_utilisateur, $texte]);
    }
}
header('Location: index.php');
exit();
?>