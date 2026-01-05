<?php
require_once("inc/init.inc.php");
if(!estConnecte()) { header("location:connexion.php"); exit(); }

$id_membre = $_SESSION['membre']['id_membre'];

// Requête pour l'historique
$resultat = $pdo->query("SELECT c.id_commande, c.date_enregistrement, p.prix, s.titre, s.photo, s.id_salle 
                        FROM commande c 
                        INNER JOIN produit p ON c.id_produit = p.id_produit 
                        INNER JOIN salle s ON p.id_salle = s.id_salle 
                        WHERE c.id_membre = '$id_membre'
                        ORDER BY c.date_enregistrement DESC");

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <div class="bg-<?= (estAdmin()) ? 'danger' : 'primary' ?> text-white rounded-circle d-inline-block p-3 mb-3 shadow">
                        <i class="bi bi-person-<?= (estAdmin()) ? 'badge-fill' : 'fill' ?> fs-1"></i>
                    </div>
                    <h4 class="fw-bold"><?= $_SESSION['membre']['pseudo'] ?></h4>
                    <p class="text-muted mb-2"><?= $_SESSION['membre']['email'] ?></p>
                    <span class="badge rounded-pill bg-<?= (estAdmin()) ? 'danger' : 'success' ?> px-3 py-2">
                        <i class="bi bi-shield-lock me-1"></i>
                        <?= (estAdmin()) ? 'Administrateur' : 'Membre' ?>
                    </span>
                </div>
            </div>

            <?php if(estAdmin()): ?>
                <div class="card border-danger shadow-sm mb-4">
                    <div class="card-header bg-danger text-white fw-bold">
                        <i class="bi bi-gear-fill me-2"></i>Actions Administrateur
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Vous avez les droits de gestion sur la plateforme Lokisalle.</p>
                        <div class="d-grid gap-2">
                            <a href="admin/statistiques.php" class="btn btn-danger btn-sm">Accéder au Dashboard</a>
                            <a href="admin/gestion_salles.php" class="btn btn-outline-danger btn-sm">Gérer les salles</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-primary shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary">Besoin d'une salle ?</h6>
                        <p class="small text-muted">Trouvez l'espace idéal pour votre prochaine réunion.</p>
                        <a href="recherche.php" class="btn btn-primary btn-sm w-100">Réserver une salle</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Historique de mes réservations</h3>
                <span class="badge bg-dark"><?= $resultat->rowCount() ?> réservation(s)</span>
            </div>
            
            <?php if($resultat->rowCount() > 0): ?>
                <div class="table-responsive shadow-sm bg-white p-3 rounded border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Visuel</th>
                                <th>Salle</th>
                                <th>Prix TTC</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($c = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr>
                                <td>
                                    <img src="photo/<?= $c['photo'] ?>" class="rounded shadow-sm" style="width: 70px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold"><?= $c['titre'] ?></div>
                                    <div class="text-muted small">ID Résa : #<?= $c['id_commande'] ?></div>
                                </td>
                                <td class="fw-bold text-primary"><?= number_format($c['prix'] * 1.2, 2) ?> €</td>
                                <td><?= date('d/m/Y', strtotime($c['date_enregistrement'])) ?></td>
                                <td class="text-center">
                                    <a href="details_commande.php?id=<?= $c['id_commande'] ?>" class="btn btn-sm btn-light border">
                                        <i class="bi bi-eye"></i> Détails
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-light border text-center py-5 shadow-sm">
                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                    <p class="mt-3 mb-0">Vous n'avez pas encore effectué de réservation.</p>
                    <a href="recherche.php" class="btn btn-link">Parcourir nos salles</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>