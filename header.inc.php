<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokisalle - Location de salles professionnelles</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; }
        .admin-link { color: #ffc107 !important; font-weight: bold; }
        /* Pour que le menu soit bien visible sur mobile */
        .dropdown-menu-dark .dropdown-item:hover { background-color: #495057; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand text-primary" href="<?= RACINE_SITE ?>index.php">LOKISALLE</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= RACINE_SITE ?>index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= RACINE_SITE ?>recherche.php">Recherche</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= RACINE_SITE ?>contact.php">Contact</a>
        </li>
      </ul>
      
      <ul class="navbar-nav ms-auto align-items-center">
        
        <?php if(estAdmin()): // MENU ADMINISTRATION ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle admin-link me-3" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-gear-fill"></i> Administration
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
              <li><a class="dropdown-item" href="<?= RACINE_SITE ?>admin/gestion_salles.php">Gestion Salles</a></li>
              <li><a class="dropdown-item" href="<?= RACINE_SITE ?>admin/gestion_produits.php">Gestion Produits</a></li>
              <li><a class="dropdown-item" href="<?= RACINE_SITE ?>admin/gestion_membres.php">Gestion Membres</a></li>
              <li><a class="dropdown-item" href="<?= RACINE_SITE ?>admin/gestion_avis.php">Gestion Avis</a></li>
              <li><a class="dropdown-item" href="<?= RACINE_SITE ?>admin/gestion_contact.php">Gestion Messages</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= RACINE_SITE ?>admin/statistiques.php">Statistiques</a></li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if(estConnecte()): // MENU MEMBRE CONNECTÉ ?>
          <li class="nav-item">
            <a class="nav-link text-info" href="<?= RACINE_SITE ?>profil.php">
                <i class="bi bi-person-circle"></i> <?= $_SESSION['membre']['pseudo'] ?>
            </a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-outline-light btn-sm" href="<?= RACINE_SITE ?>connexion.php?action=deconnexion">Déconnexion</a>
          </li>
        <?php else: // MENU VISITEUR ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= RACINE_SITE ?>connexion.php">Connexion</a>
          </li>
          <li class="nav-item ms-lg-2">
            <a class="btn btn-primary btn-sm" href="<?= RACINE_SITE ?>inscription.php">S'inscrire</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4"> ```


<main class="container py-4">`