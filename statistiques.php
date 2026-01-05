<?php
require_once("../inc/init.inc.php");

// Sécurité : Seul l'admin entre ici
if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

require_once("../inc/header.inc.php");

// Requêtes pour les statistiques (Top 5)
// 1. Les 5 salles les mieux notées
$top_notes = $pdo->query("SELECT s.titre, AVG(a.note) as moyenne 
                         FROM salle s 
                         JOIN avis a ON s.id_salle = a.id_salle 
                         GROUP BY s.id_salle 
                         ORDER BY moyenne DESC LIMIT 5");

// 2. Les 5 salles les plus louées
$top_louees = $pdo->query("SELECT s.titre, COUNT(c.id_commande) as nb_resas 
                          FROM salle s 
                          JOIN produit p ON s.id_salle = p.id_salle 
                          JOIN commande c ON p.id_produit = c.id_produit 
                          GROUP BY s.id_salle 
                          ORDER BY nb_resas DESC LIMIT 5");
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col">
            <h1 class="border-bottom pb-2"><i class="bi bi-speedometer2"></i> Tableau de Bord Admin</h1>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Salles</h6>
                    <p class="display-6 fw-bold"><?= $pdo->query("SELECT COUNT(*) FROM salle")->fetchColumn(); ?></p>
                    <a href="gestion_salles.php" class="text-white-50 small text-decoration-none">Gérer les lieux →</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Réservations</h6>
                    <p class="display-6 fw-bold"><?= $pdo->query("SELECT COUNT(*) FROM commande")->fetchColumn(); ?></p>
                    <a href="gestion_commandes.php" class="text-white-50 small text-decoration-none">Voir les ventes →</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Membres</h6>
                    <p class="display-6 fw-bold"><?= $pdo->query("SELECT COUNT(*) FROM membre")->fetchColumn(); ?></p>
                    <a href="gestion_membres.php" class="text-white-50 small text-decoration-none">Liste clients →</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase small">Avis</h6>
                    <p class="display-6 fw-bold"><?= $pdo->query("SELECT COUNT(*) FROM avis")->fetchColumn(); ?></p>
                    <a href="gestion_avis.php" class="text-dark-50 small text-decoration-none">Modérer →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold bg-white">⭐ Top 5 des salles les mieux notées</div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead><tr><th>Salle</th><th>Note moyenne</th></tr></thead>
                        <tbody>
                            <?php while($row = $top_notes->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr><td><?= $row['titre'] ?></td><td><span class="badge bg-warning text-dark"><?= round($row['moyenne'], 1) ?> / 10</span></td></tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold bg-white">📈 Top 5 des salles les plus louées</div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead><tr><th>Salle</th><th>Nombre de réservations</th></tr></thead>
                        <tbody>
                            <?php while($row = $top_louees->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr><td><?= $row['titre'] ?></td><td><span class="badge bg-success"><?= $row['nb_resas'] ?> résas</span></td></tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>