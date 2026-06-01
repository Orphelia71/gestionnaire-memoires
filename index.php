<?php 
include 'db.php'; 

$search = $_GET['q'] ?? '';
$filiere = $_GET['filiere'] ?? '';
$centre = $_GET['centre'] ?? '';
$prof = $_GET['prof'] ?? '';

$sql = "SELECT * FROM memoires WHERE statut = 'PUBLIE'";
$params = [];
if (!empty($search)) { $sql .= " AND titre LIKE ?"; $params[] = "%$search%"; }
if (!empty($filiere)) { $sql .= " AND filiere = ?"; $params[] = $filiere; }
if (!empty($centre)) { $sql .= " AND centre = ?"; $params[] = $centre; }
if (!empty($prof)) { $sql .= " AND professeur LIKE ?"; $params[] = "%$prof%"; }

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$memoires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche de Mémoires</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f9f9f9; margin: 40px; }
        .filter-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
        input, button { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; box-sizing: border-box; }
        button { background: #007BFF; color: white; cursor: pointer; font-weight: bold; border: none; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 15px; }
        .btn-download { display: inline-block; background: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; font-size: 14px; margin-top: 10px; }
        .btn-like { background: #e0e0e0; color: #333; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 14px; margin-top: 10px; width: auto; display: inline-block;}
        .btn-like.liked { background: #ff4757; color: white; }
    </style>
</head>
<body>

    <h2>🔍 Recherche et Consultation</h2>

    <div class="filter-container">
        <form method="GET" action="">
            <div class="filter-grid">
                <input type="text" name="q" placeholder="Mot-clé..." value="<?= htmlspecialchars($search) ?>">
                <input type="text" name="filiere" placeholder="Filière" value="<?= htmlspecialchars($filiere) ?>">
                <input type="text" name="centre" placeholder="Centre" value="<?= htmlspecialchars($centre) ?>">
                <input type="text" name="prof" placeholder="Professeur" value="<?= htmlspecialchars($prof) ?>">
            </div>
            <button type="submit">Appliquer les filtres</button>
        </form>
    </div>

    <h3>Mémoires disponibles (<?= count($memoires) ?>)</h3>
    <div>
        <?php foreach ($memoires as $m): ?>
            <div class="card">
                <h3><?= htmlspecialchars($m['titre']) ?></h3>
                <p><strong>Filière :</strong> <?= htmlspecialchars($m['filiere']) ?> | <strong>Centre :</strong> <?= htmlspecialchars($m['centre']) ?></p>
                <p><strong>Encadrant :</strong> Pr. <?= htmlspecialchars($m['professeur']) ?></p>
                <p style="background: #fdfdfd; padding: 10px; border-left: 3px solid #007BFF; font-style: italic; color: #555;"><strong>Résumé :</strong> <?= htmlspecialchars($m['resume'] ?? 'Aucun résumé disponible.') ?></p>
                <div style="margin: 15px 0; border: 1px solid #ccc; border-radius: 6px; overflow: hidden;">
                    <iframe src="uploads/<?= $m['nom_fichier'] ?>#toolbar=0&navpanes=0"  width="100%" height="500px" style="border: none;">Votre navigateur ne prend pas en charge l'affichage des PDF.</iframe>
                </div>
                
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #ccc;">
                    <h4>💬 Commentaires :</h4>
    
                    <form action="commentaire.php" method="POST" style="display: flex; gap: 10px; margin-bottom: 15px;">
                        <input type="hidden" name="id_memoire" value="<?= $m['id'] ?>">
                        <input type="hidden" name="id_utilisateur" value="1"> 
                        <input type="text" name="texte_commentaire" placeholder="Écrivez un commentaire..." required style="flex-grow: 1;">
                        <button type="submit" name="submit_commentaire" style="background: #28a745; width: auto; white-space: nowrap;">Envoyer</button>
                    </form>

                    <div class="liste-commentaires" style="max-height: 200px; overflow-y: auto; background: #f1f1f1; padding: 10px; border-radius: 5px;">
        <?php
        $stmt_comm = $pdo->prepare("SELECT c.*, u.nom_utilisateur FROM commentaires c JOIN utilisateurs u ON c.id_utilisateur = u.id WHERE c.id_memoire = ? ORDER BY c.date_pub DESC");
        $stmt_comm->execute([$m['id']]);
        $commentaires = $stmt_comm->fetchAll(PDO::FETCH_ASSOC);

        if (empty($commentaires)): 
        ?>
            <p style="font-size: 13px; color: #777; margin: 0;">Aucun commentaire pour le moment.</p>
        <?php else: ?>
            <?php foreach ($commentaires as $c): ?>
                <div style="font-size: 13px; margin-bottom: 8px; border-bottom: 1px solid #e0e0e0; padding-bottom: 5px;">
                    <strong>@<?= htmlspecialchars($c['nom_utilisateur']) ?></strong> 
                    <span style="color: #999; font-size: 11px;">(le <?= date('d/m H:i', strtotime($c['date_pub'])) ?>) :</span>
                    <p style="margin: 3px 0 0 0; color: #444;"><?= htmlspecialchars($c['texte']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
                
                <button class="btn-like" onclick="likerMemoire(<?= $m['id'] ?>, 1, this)">❤️ Like</button>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    function likerMemoire(idMemoire, idUtilisateur, boutonClike) {
        fetch('like.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_memoire=' + idMemoire + '&id_utilisateur=' + idUtilisateur
        })
        .then(response => response.text())
        .then(resultat => {
            if (resultat === "liked") {
                boutonClike.classList.add('liked');
                boutonClike.innerText = "❤️ Liké !";
            } else if (resultat === "unliked") {
                boutonClike.classList.remove('liked');
                boutonClike.innerText = "❤️ Like";
            } else {
                alert("Une erreur est survenue.");
            }
        });
    }
    </script>
</body>
</html>