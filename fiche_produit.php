<?php
require_once("inc/init.inc.php");

// 1. On récupère l'ID du produit depuis l'URL
if(!isset($_GET['id_produit'])) {
    header("location:index.php");
    exit();
}

// 2. Requête SQL pour avoir les infos de la salle ET du produit
$resultat = $pdo->query("SELECT s.*, p.* FROM salle s 
                        INNER JOIN produit p ON s.id_salle = p.id_salle 
                        WHERE p.id_produit = '$_GET[id_produit]'");

$produit = $resultat->fetch(PDO::FETCH_ASSOC);

// Si le produit n'existe pas ou n'est plus libre
if(!$produit || $produit['etat'] == 'reserve') {
    header("location:index.php");
    exit();
}

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="fw-bold mb-3"><?= $produit['titre'] ?></h1>
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <img src="photo/<?= $produit['photo'] ?>" class="img-fluid" alt="<?= $produit['titre'] ?>" style="width: 100%; height: 450px; object-fit: cover;">
            </div>
            
            <h4 class="mt-4">Description</h4>
            <p class="text-muted leading-relaxed"><?= nl2br($produit['description']) ?></p>
            
            <hr class="my-4">
            
            <div class="row text-center mb-4">
                <div class="col-4">
                    <i class="bi bi-people fs-3 text-primary"></i>
                    <p class="small mb-0">Capacité</p>
                    <strong><?= $produit['capacite'] ?> pers.</strong>
                </div>
                <div class="col-4">
                    <i class="bi bi-tag fs-3 text-primary"></i>
                    <p class="small mb-0">Catégorie</p>
                    <strong><?= ucfirst($produit['categorie']) ?></strong>
                </div>
                <div class="col-4">
                    <i class="bi bi-geo-alt fs-3 text-primary"></i>
                    <p class="small mb-0">Ville</p>
                    <strong><?= ucfirst($produit['ville']) ?></strong>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-lg border-0 sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="card-title text-center mb-4">Informations de location</h5>
                    
                    <div class="mb-3">
                        <label class="small text-muted">Date d'arrivée</label>
                        <div class="fw-bold"><i class="bi bi-calendar-check me-2"></i><?= date('d/m/Y H:i', strtotime($produit['date_arrivee'])) ?></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="small text-muted">Date de départ</label>
                        <div class="fw-bold"><i class="bi bi-calendar-x me-2"></i><?= date('d/m/Y H:i', strtotime($produit['date_depart'])) ?></div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center my-4">
                        <span class="display-5 fw-bold text-primary"><?= number_format($produit['prix'] * 1.2, 2) ?> €</span>
                        <p class="text-muted">TTC (TVA 20%)</p>
                    </div>

                    <?php if(estConnecte()) : ?>
                        <form action="reservation.php" method="POST">
                            <input type="hidden" name="id_produit" value="<?= $produit['id_produit'] ?>">
                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow">
                                <i class="bi bi-lightning-fill"></i> Réserver maintenant
                            </button>
                        </form>
                    <?php else : ?>
                        <div class="alert alert-warning text-center small">
                            Veuillez vous <a href="connexion.php" class="alert-link">connecter</a> pour réserver cette salle.
                        </div>
                        <a href="inscription.php" class="btn btn-outline-primary w-100">Créer un compte</a>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <a href="index.php" class="text-decoration-none small text-muted"><i class="bi bi-arrow-left"></i> Retour aux offres</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>