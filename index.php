<?php
require_once("inc/init.inc.php");

// Récupération des 3 dernières offres (Zone 4 du PDF)
// Note : J'ai adapté la requête pour correspondre à ton dossier 'photo/'
$requete = $pdo->query("SELECT p.*, s.titre, s.photo, s.ville 
                        FROM produit p 
                        INNER JOIN salle s ON p.id_salle = s.id_salle 
                        WHERE p.etat = 'libre' AND p.date_arrivee >= NOW() 
                        ORDER BY p.date_arrivee ASC LIMIT 3");

require_once("inc/header.inc.php"); 
?>

<section class="py-5 mb-5 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">Bienvenue chez <span class="text-primary">LOKISALLE</span></h1>
                <p class="lead mt-3">Spécialiste de la location de salles pour vos réunions, formations ou bureaux temporaires à Paris, Lyon et Marseille. Découvrez nos espaces modernes, équipés et prêts à l'emploi.</p>
                <div class="mt-4">
                    <a href="recherche.php" class="btn btn-primary btn-lg px-4 me-md-2">Trouver une salle</a>
                    <a href="contact.php" class="btn btn-outline-secondary btn-lg px-4">Nous contacter</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded shadow" alt="Espace de réunion">
            </div>
        </div>
    </div>
</section>

<main class="container">
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold mb-0">Nos 3 dernières offres</h2>
                <p class="text-muted">Les créneaux les plus proches de chez vous</p>
            </div>
            <a href="recherche.php" class="text-primary text-decoration-none fw-bold">Voir toutes les offres <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row">
            <?php if($requete->rowCount() > 0): ?>
                <?php while($p = $requete->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 border-0 transition-hover">
                            <div class="position-relative">
                                <img src="photo/<?= $p['photo'] ?>" class="card-img-top" alt="<?= $p['titre'] ?>" style="height:220px; object-fit:cover;">
                                <div class="badge bg-dark position-absolute bottom-0 end-0 m-3 fs-5">
                                    <?= number_format($p['prix'] * 1.2, 0) ?> € TTC
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="card-title fw-bold mb-0"><?= $p['titre'] ?></h5>
                                    <span class="text-muted small">📍 <?= $p['ville'] ?></span>
                                </div>
                                <p class="card-text text-muted">
                                    <i class="bi bi-calendar3 me-2"></i>Du <?= date('d/m/Y', strtotime($p['date_arrivee'])) ?>
                                    <br>
                                    <i class="bi bi-calendar3-fill me-2"></i>Au <?= date('d/m/Y', strtotime($p['date_depart'])) ?>
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>" class="btn btn-outline-primary w-100">Voir la fiche détaillée</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info py-4 text-center">
                        <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                        Aucune offre n'est disponible pour le moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-5 text-center bg-white rounded shadow-sm mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <i class="bi bi-shield-check fs-1 text-primary"></i>
                <h5 class="mt-3">Paiement Sécurisé</h5>
            </div>
            <div class="col-md-4">
                <i class="bi bi-clock-history fs-1 text-primary"></i>
                <h5 class="mt-3">Disponibilité 24/7</h5>
            </div>
            <div class="col-md-4">
                <i class="bi bi-chat-dots fs-1 text-primary"></i>
                <h5 class="mt-3">Support Client</h5>
            </div>
        </div>
    </section>
</main>

<?php require_once("inc/footer.inc.php"); ?>