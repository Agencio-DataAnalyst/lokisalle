<?php
require_once("../inc/init.inc.php");

// ... (haut du fichier identique)

if($_POST) {
    try {
        $photo_bdd = "";

        if(!empty($_FILES['photo']['name'])) {
            $nom_photo = $_POST['titre'] . '_' . $_FILES['photo']['name'];
            $photo_bdd = $nom_photo;
            $chemin_photo = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . "photo/" . $nom_photo;
            
            if(!copy($_FILES['photo']['tmp_name'], $chemin_photo)) {
                $content .= "<div class='alert alert-danger'>Erreur lors du transfert de la photo. Vérifiez les droits du dossier /photo/</div>";
            }
        }

        $enregistrement = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
        
        $enregistrement->execute([
            ':titre'       => $_POST['titre'],
            ':description' => $_POST['description'],
            ':photo'       => $photo_bdd,
            ':pays'        => $_POST['pays'],
            ':ville'       => $_POST['ville'],
            ':adresse'     => $_POST['adresse'],
            ':cp'          => $_POST['cp'],
            ':capacite'    => $_POST['capacite'],
            ':categorie'   => $_POST['categorie']
        ]);
        
        $content .= "<div class='alert alert-success text-center'>La salle <strong>" . htmlspecialchars($_POST['titre']) . "</strong> a été ajoutée.</div>";
    } catch (Exception $e) {
        // C'est ici que tu verras l'erreur SQL s'il y en a une
        $content .= "<div class='alert alert-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
    }
}
// ...



if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

// 1. SUPPRESSION
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_salle'])) {
    // On pourrait aussi supprimer le fichier image physiquement ici avec unlink()
    $pdo->query("DELETE FROM salle WHERE id_salle = " . (int)$_GET['id_salle']);
    $content .= "<div class='alert alert-success'>La salle a bien été supprimée.</div>";
}

// 2. ENREGISTREMENT
if($_POST) {
    $photo_bdd = "";

    if(!empty($_FILES['photo']['name'])) {
        $nom_photo = $_POST['titre'] . '_' . $_FILES['photo']['name'];
        $photo_bdd = $nom_photo;
        $chemin_photo = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . "photo/" . $nom_photo;
        copy($_FILES['photo']['tmp_name'], $chemin_photo);
    }

    $enregistrement = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
    
    $enregistrement->execute([
        ':titre'       => $_POST['titre'],
        ':description' => $_POST['description'],
        ':photo'       => $photo_bdd,
        ':pays'        => $_POST['pays'],
        ':ville'       => $_POST['ville'],
        ':adresse'     => $_POST['adresse'],
        ':cp'           => $_POST['cp'],
        ':capacite'    => $_POST['capacite'],
        ':categorie'   => $_POST['categorie']
    ]);
    
    $content .= "<div class='alert alert-success text-center'>La salle <strong>" . $_POST['titre'] . "</strong> a été ajoutée.</div>";
}

// 3. RÉCUPÉRATION POUR L'AFFICHAGE
$resultat = $pdo->query("SELECT * FROM salle");

require_once("../inc/header.inc.php");
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-building-add text-primary"></i> Gestion des Salles</h1>
        <div class="btn-group">
            <a href="#liste" class="btn btn-outline-primary active">Liste des salles</a>
            <a href="#formulaire" class="btn btn-primary" data-bs-toggle="collapse">Ajouter une salle</a>
        </div>
    </div>

    <?= $content ?>

    <div class="collapse mb-5" id="formulaire">
        <div class="card card-body shadow border-0">
            <h5 class="card-title mb-4">Nouvelle Salle</h5>
            <form method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Titre</label>
                        <input type="text" name="titre" class="form-control" placeholder="ex: Salle Opéra" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Catégorie</label>
                        <select name="categorie" class="form-select">
                            <option value="reunion">Réunion</option>
                            <option value="bureau">Bureau</option>
                            <option value="formation">Formation</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Décrivez les équipements..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3"><label class="form-label">Pays</label><input type="text" name="pays" class="form-control" value="France"></div>
                    <div class="col-md-3 mb-3"><label class="form-label">Ville</label><input type="text" name="ville" class="form-control" placeholder="Paris"></div>
                    <div class="col-md-3 mb-3"><label class="form-label">Code Postal</label><input type="text" name="cp" class="form-control"></div>
                    <div class="col-md-3 mb-3"><label class="form-label fw-bold">Capacité max</label><input type="number" name="capacite" class="form-control" placeholder="10"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" placeholder="12 rue de la Paix">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success px-5"><i class="bi bi-check-lg"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Aperçu</th><th>Titre / Ville</th><th>Catégorie</th><th>Capacité</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($salle = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr>
                        <td><?= $salle['id_salle'] ?></td>
                        <td>
                            <?php if(!empty($salle['photo'])): ?>
                                <img src="<?= RACINE_SITE ?>photo/<?= $salle['photo'] ?>" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light text-muted text-center rounded small py-2" style="width: 80px;">No photo</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold"><?= $salle['titre'] ?></div>
                            <div class="small text-muted"><?= $salle['ville'] ?> (<?= $salle['cp'] ?>)</div>
                        </td>
                        <td><span class="badge bg-secondary"><?= $salle['categorie'] ?></span></td>
                        <td><i class="bi bi-people me-1"></i> <?= $salle['capacite'] ?></td>
                        <td>
                            <a href="?action=modifier&id_salle=<?= $salle['id_salle'] ?>" class="text-primary me-3"><i class="bi bi-pencil-square"></i></a>
                            <a href="?action=supprimer&id_salle=<?= $salle['id_salle'] ?>" class="text-danger" onclick="return confirm('Supprimer cette salle ?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>