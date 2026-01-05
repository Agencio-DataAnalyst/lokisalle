<?php
require_once("../inc/init.inc.php");

if(!estAdmin()) {
    header("location:../connexion.php");
    exit();
}

// 1. Suppression d'une commande
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_commande'])) {
    $pdo->query("DELETE FROM commande WHERE id_commande = " . (int)$_GET['id_commande']);
    $content .= "<div class='alert alert-success'>La commande #" . $_GET['id_commande'] . " a été supprimée avec succès.</div>";
}

// 2. Récupération des commandes avec les infos liées
$requete = "SELECT c.id_commande, c.date_enregistrement, m.email, m.pseudo, s.titre, p.date_arrivee, p.date_depart, p.prix 
            FROM commande c
            INNER JOIN membre m ON c.id_membre = m.id_membre
            INNER JOIN produit p ON c.id_produit = p.id_produit
            INNER JOIN salle s ON p.id_salle = s.id_salle
            ORDER BY c.date_enregistrement DESC";
$resultat = $pdo->query($requete);

$total_ca = 0;

require_once("../inc/header.inc.php");
?>

<div class="container mt-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="fw-bold"><i class="bi bi-cart-check text-success"></i> Gestion des Commandes</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="badge bg-dark fs-6">Ventes : <?= $resultat->rowCount() ?></span>
        </div>
    </div>

    <?= $content ?>

    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle mb-0 bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Salle</th>
                    <th>Période de location</th>
                    <th>Montant HT</th>
                    <th>Montant TTC</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($resultat->rowCount() > 0): ?>
                    <?php while($commande = $resultat->fetch(PDO::FETCH_ASSOC)) : 
                        $ttc = $commande['prix'] * 1.2;
                        $total_ca += $ttc;
                    ?>
                    <tr>
                        <td class="fw-bold">#<?= $commande['id_commande'] ?></td>
                        <td>
                            <div class="fw-bold"><?= $commande['pseudo'] ?></div>
                            <div class="small text-muted"><?= $commande['email'] ?></div>
                        </td>
                        <td><span class="badge bg-info text-dark"><?= $commande['titre'] ?></span></td>
                        <td>
                            <div class="small">
                                <i class="bi bi-calendar-event"></i> Du <?= date('d/m/Y', strtotime($commande['date_arrivee'])) ?><br>
                                <i class="bi bi-calendar-check"></i> Au <?= date('d/m/Y', strtotime($commande['date_depart'])) ?>
                            </div>
                        </td>
                        <td><?= number_format($commande['prix'], 2) ?> €</td>
                        <td class="fw-bold text-primary"><?= number_format($ttc, 2) ?> €</td>
                        <td class="text-center">
                            <a href="?action=supprimer&id_commande=<?= $commande['id_commande'] ?>" 
                               class="btn btn-outline-danger btn-sm" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Aucune commande enregistrée pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="table-light border-top border-2">
                <tr>
                    <td colspan="5" class="text-end fw-bold fs-5 text-uppercase">Chiffre d'affaires Total :</td>
                    <td colspan="2" class="fw-bold fs-5 text-success"><?= number_format($total_ca, 2) ?> € TTC</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php require_once("../inc/footer.inc.php"); ?>