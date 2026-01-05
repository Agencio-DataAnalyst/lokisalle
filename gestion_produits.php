<?php
require_once("../inc/init.inc.php");

if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

// 1. SUPPRESSION D'UN PRODUIT
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_produit'])) {
    $pdo->query("DELETE FROM produit WHERE id_produit = " . (int)$_GET['id_produit']);
    $content .= "<div class='alert alert-warning text-center'>L'offre a été retirée du catalogue.</div>";
}

// 2. ENREGISTREMENT D'UN PRODUIT
if($_POST) {
    // Petite vérification de sécurité sur les dates
    if($_POST['date_arrivee'] >= $_POST['date_depart']) {
        $content .= "<div class='alert alert-danger text-center'>Erreur : La date de départ doit être après la date d'arrivée.</div>";
    } else {
        $insertion = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :date_arrivee, :date_depart, :prix, 'libre')");
        
        $insertion->execute([
            ':id_salle'     => $_POST['id_salle'],
            ':date_arrivee' => $_POST['date_arrivee'],
            ':date_depart'  => $_POST['date_depart'],
            ':prix'         => $_POST['prix']
        ]);
        
        $content .= "<div class='alert alert-success text-center'>L'offre a été publiée avec succès !</div>";
    }
}

// 3. RÉCUPÉRATION DES DONNÉES POUR L'AFFICHAGE
// On fait une jointure pour voir le nom de la salle au lieu de juste son ID
$requete = "SELECT p.*, s.titre, s.ville 
            FROM produit p 
            INNER JOIN salle s ON p.id_salle = s.id_salle 
            ORDER BY p.date_arrivee ASC";
$resultat_produits = $pdo->query($requete);

// On récupère les salles pour le menu déroulant du formulaire
$liste_salles = $pdo->query("SELECT id_salle, titre, ville FROM salle ORDER BY titre ASC");

require_once("../inc/header.inc.php");
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-calendar-plus text-primary"></i> Gestion des Produits</h1>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#formulaireProduit">
            <i class="bi bi-plus-circle me-2"></i>Nouveau créneau
        </button>
    </div>

    <?= $content ?>

    <div class="collapse mb-5" id="formulaireProduit">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="post">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Salle</label>
                            <select name="id_salle" class="form-select" required>
                                <?php while($salle = $liste_salles->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <option value="<?= $salle['id_salle'] ?>">
                                        <?= $salle['titre'] ?> - <?= $salle['ville'] ?> (ID: <?= $salle['id_salle'] ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Date d'arrivée</label>
                            <input type="datetime-local" name="date_arrivee" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Date de départ</label>
                            <input type="datetime-local" name="date_depart" class="form-control" required>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Prix HT</label>
                            <div class="input-group">
                                <input type="number" name="prix" class="form-control" placeholder="Ex: 450" required>
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                        <div class="col-md-8 mb-3 text-end">
                            <button type="submit" class="btn btn-success btn-lg px-5">Publier l'offre</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Salle</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Prix HT / TTC</th>
                        <th>État</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($prod = $resultat_produits->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr class="<?= ($prod['etat'] == 'reservation') ? 'table-light opacity-75' : '' ?>">
                        <td><?= $prod['id_produit'] ?></td>
                        <td>
                            <div class="fw-bold"><?= $prod['titre'] ?></div>
                            <small class="text-muted"><?= $prod['ville'] ?></small>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($prod['date_arrivee'])) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($prod['date_depart'])) ?></td>
                        <td>
                            <span class="fw-bold text-primary"><?= number_format($prod['prix'], 0) ?> €</span> / 
                            <small class="text-muted"><?= number_format($prod['prix']*1.2, 0) ?> €</small>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-<?= ($prod['etat'] == 'libre') ? 'success' : 'danger' ?>">
                                <?= ($prod['etat'] == 'libre') ? 'Disponible' : 'Réservé' ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="?action=supprimer&id_produit=<?= $prod['id_produit'] ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Retirer cette offre définitivement ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>