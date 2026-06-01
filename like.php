<?php
include 'db.php';

if (isset($_POST['id_memoire']) && isset($_POST['id_utilisateur'])) {
    $id_memoire = intval($_POST['id_memoire']);
    $id_utilisateur = intval($_POST['id_utilisateur']);
    $check = $pdo->prepare("SELECT * FROM likes WHERE id_memoire = ? AND id_utilisateur = ?");
    $check->execute([$id_memoire, $id_utilisateur]);

    if ($check->rowCount() == 0) {
        $ins = $pdo->prepare("INSERT INTO likes (id_memoire, id_utilisateur) VALUES (?, ?)");
        $ins->execute([$id_memoire, $id_utilisateur]);
        echo "liked"; 
    } else {
        $del = $pdo->prepare("DELETE FROM likes WHERE id_memoire = ? AND id_utilisateur = ?");
        $del->execute([$id_memoire, $id_utilisateur]);
        echo "unliked";
    }
}
?>