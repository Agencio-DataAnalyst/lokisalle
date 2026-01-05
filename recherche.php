<?php
require_once("inc/init.inc.php");

// 1. Initialisation de la requête de base
// On cherche les produits LIBRES avec les infos de la salle
$sql = "SELECT s.*, p.* FROM salle s 
        INNER JOIN produit p ON s.id_salle = p.id_salle 
        WHERE p.etat = 'libre'";

// 2. Ajout dynamique des filtres si l'utilisateur les a remplis
if (!empty($_GET['ville'])) {
    $sql .= " AND s.ville = " . $pdo->quote($_GET['ville']);
}

if (!empty($_GET['categorie'])) {
    $sql .= " AND s.categorie = " . $pdo->quote($_GET['categorie']);
}

if (!empty($_GET['capacite'])) {
    // On cherche les salles qui peuvent accueillir AU MOINS ce nombre de personnes
    $sql .= " AND s.capacite >= " . intval($_GET['capacite']);
}

// 3. Exécution de la recherche
$resultat = $pdo->query($sql);

require_once("inc/header.inc.php");
?>

<div class="container mt-5">
    <h2 class="mb-4"><i class="bi bi-search"></i> Rechercher une salle</h2>

    <div class="card shadow-sm border-0 p-4 mb-5 bg-light">
        <form method="GET" action="recherche.php" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-bold">Ville</label>
                <select name="ville" class="form-select">
                    <option value="">Toutes les villes</option>
                    <?php
                    $villes = $pdo->query("SELECT DISTINCT ville FROM salle");
                    while($v = $villes->fetch(PDO::FETCH_ASSOC)) {
                        $selected = (isset($_GET['ville']) && $_GET['ville'] == $v['ville']) ? 'selected' : '';
                        echo "<option value='$v[ville]' $selected>$v[ville]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Catégorie</label>
                <select name="categorie" class="form-select">
                    <option value="">Toutes les catégories</option>
                    <?php
                    $cats = $pdo->query("SELECT DISTINCT categorie FROM salle");
                    while($c = $cats->fetch(PDO::FETCH_ASSOC)) {
                        $selected = (isset($_GET['categorie']) && $_GET['categorie'] == $c['categorie']) ? 'selected' : '';
                        echo "<option value='$c[categorie]' $selected>$c[categorie]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Capacité min.</label>
                <input type="number" name="capacite" class="form-control" placeholder="Ex: 10" value="<?= $_GET['capacite'] ?? '' ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrer les résultats</button>
            </div>
        </form>
    </div>

    <div class="row">
        <?php if($resultat->rowCount() > 0) : ?>
            <p class="text-muted mb-4"><?= $resultat->rowCount() ?> salle(s) trouvée(s)</p>
            <?php while($p = $resultat->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="photo/<?= $p['photo'] ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $p['titre'] ?></h5>
                            <p class="small text-primary fw-bold mb-1">
                                <?= date('d/m/Y', strtotime($p['date_arrivee'])) ?> au <?= date('d/m/Y', strtotime($p['date_depart'])) ?>
                            </p>
                            <p class="card-text text-truncate"><?= $p['description'] ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0"><?= number_format($p['prix'] * 1.2, 2) ?> €</span>
                                <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>" class="btn btn-sm btn-outline-dark">Détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="alert alert-warning text-center">
                <i class="bi bi-info-circle"></i> Aucun résultat pour ces critères. Essayez d'élargir votre recherche.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once("inc/footer.inc.php"); ?>