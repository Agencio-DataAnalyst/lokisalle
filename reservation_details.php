<?php require_once("inc/init.inc.php"); 

if(!isset($_GET['id_produit'])) { header("location:index.php"); exit(); }

$res = $pdo->prepare("SELECT p.*, s.* FROM produit p JOIN salle s ON p.id_salle = s.id_salle WHERE p.id_produit = ?");
$res->execute([$_GET['id_produit']]);
$p = $res->fetch(PDO::FETCH_ASSOC);

// Calcul du prix TTC (Page 10 du PDF)
$prix_ht = $p['prix'];
$tva = $prix_ht * 0.20;
$prix_ttc = $prix_ht + $tva;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $p['titre'] ?> - Détails</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row bg-white p-4 shadow-sm rounded">
            <!-- Image et titre -->
            <div class="col-md-6 text-center">
                <img src="<?= $p['photo'] ?>" class="img-fluid rounded mb-3 border">
                <h2><?= $p['titre'] ?></h2>
            </div>

            <!-- Informations (Zone 3 et 4 du PDF) -->
            <div class="col-md-6 border-start ps-4">
                <h4 class="text-primary">Détails de la réservation</h4>
                <p><strong>Ville :</strong> <?= $p['ville'] ?></p>
                <p><strong>Adresse :</strong> <?= $p['adresse'] ?></p>
                <p><strong>Capacité :</strong> <?= $p['capacite'] ?> personnes</p>
                <p><strong>Dates :</strong> Du <?= date('d/m/Y H:i', strtotime($p['date_arrivee'])) ?> au <?= date('d/m/Y H:i', strtotime($p['date_depart'])) ?></p>
                
                <hr>
                <div class="bg-light p-3 rounded">
                    <p class="mb-1 text-muted">Prix HT : <?= $prix_ht ?> €</p>
                    <p class="mb-1 text-muted">TVA (20%) : <?= $tva ?> €</p>
                    <h3 class="text-success">Prix Total : <?= $prix_ttc ?> €</h3>
                </div>

                <!-- Bouton Panier (Page 10) -->
                <?php if(estConnecte()): ?>
                    <form action="panier.php" method="post" class="mt-4">
                        <input type="hidden" name="id_produit" value="<?= $p['id_produit'] ?>">
                        <button type="submit" name="ajout_panier" class="btn btn-warning w-100 btn-lg fw-bold">Ajouter au panier</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning mt-4 text-center">
                        <a href="connexion.php">Veuillez vous connecter pour réserver</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>