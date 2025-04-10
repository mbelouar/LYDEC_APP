<?php
include_once "../../traitement/fournisseureDashboard.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Fournisseur - Gestion d'Électricité</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../../assets/css/fournisseur-style.css">
</head>
<body>

<!-- Page Loader -->
<div class="page-loader">
  <img src="../../uploads/Lydec.png" alt="Lydec" class="loader-logo">
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
      <img src="../../uploads/Lydec.png" alt="Lydec" class="logo">
      <div class="brand-text">
        <span class="brand-name">Lydec</span>
        <small class="brand-tagline d-none d-sm-inline">Électricité & Eau</small>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="dashboard.php">
            <i class="fas fa-home me-1"></i> Tableau de bord
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="clients.php">
            <i class="fas fa-users me-1"></i> Clients
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="consumption.php">
            <i class="fas fa-tachometer-alt me-1"></i> Consommations
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="reclamations.php">
            <i class="fas fa-comment-alt me-1"></i> Réclamations
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../traitement/fournisseurTraitement.php?action=logout">
            <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0" data-aos="fade-right">
      <i class="fas fa-tachometer-alt me-2 text-primary"></i>
      Tableau de Bord Fournisseur
    </h1>
    <div data-aos="fade-left">
      <span class="badge bg-primary p-2">
        <i class="fas fa-calendar me-1"></i> <?php echo $dateActuelle; ?>
      </span>
    </div>
  </div>

  <!-- Stats Row -->
  <div class="row" data-aos="fade-up">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-uppercase mb-1">Clients</div>
              <div class="h5 mb-0 font-weight-bold"><?php echo $nbClients; ?></div>
            </div>
            <div class="fa-3x text-white-50 opacity-25">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-uppercase mb-1">Réclamations</div>
              <div class="h5 mb-0 font-weight-bold"><?php echo $nbReclamations; ?></div>
            </div>
            <div class="fa-3x text-white-50 opacity-25">
              <i class="fas fa-comment-alt"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-uppercase mb-1">Consommations</div>
              <div class="h5 mb-0 font-weight-bold"><?php echo $nbConsommations; ?></div>
            </div>
            <div class="fa-3x text-white-50 opacity-25">
              <i class="fas fa-tachometer-alt"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-uppercase mb-1">Jour de saisie</div>
              <div class="h5 mb-0 font-weight-bold"><?php echo $estJourSaisie ? 'Actif' : 'Inactif'; ?></div>
            </div>
            <div class="fa-3x text-white-50 opacity-25">
              <i class="fas fa-calendar-check"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Features Section -->
  <div class="card shadow mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-white">
        <i class="fas fa-cogs me-2"></i> Fonctionnalités Fournisseur
      </h6>
    </div>
    <div class="card-body">
      <div class="row g-4">
        <div class="col-lg-6 mb-4">
          <div class="card feature-card h-100 shadow-sm">
            <div class="card-body">
              <div class="feature-icon">
                <i class="fas fa-users"></i>
              </div>
              <h5 class="card-title">Gestion des Clients</h5>
              <p class="card-text">Ajouter, modifier et gérer les informations des clients du système.</p>
              <a href="clients.php" class="btn btn-sm btn-primary mt-2">
                <i class="fas fa-arrow-right me-1"></i> Accéder
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6 mb-4">
          <div class="card feature-card h-100 shadow-sm">
            <div class="card-body">
              <div class="feature-icon">
                <i class="fas fa-comment-alt"></i>
              </div>
              <h5 class="card-title">Traitement des Réclamations</h5>
              <p class="card-text">Traiter les réclamations soumises par les clients et leur envoyer des notifications.</p>
              <a href="reclamations.php" class="btn btn-sm btn-primary mt-2">
                <i class="fas fa-arrow-right me-1"></i> Accéder
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6 mb-4">
          <div class="card feature-card h-100 shadow-sm">
            <div class="card-body">
              <div class="feature-icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <h5 class="card-title">Gestion des Anomalies</h5>
              <p class="card-text">Traiter les anomalies de saisie et modifier les consommations avant la génération des factures.</p>
              <a href="consumption.php" class="btn btn-sm btn-primary mt-2">
                <i class="fas fa-arrow-right me-1"></i> Accéder
              </a>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6 mb-4">
          <div class="card feature-card h-100 shadow-sm">
            <div class="card-body">
              <div class="feature-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <h5 class="card-title">Activation des Saisies</h5>
              <p class="card-text">Activer la saisie des consommations le 18 de chaque mois pour les clients.</p>
              <?php if ($estJourSaisie): ?>
              <a href="#" class="btn btn-sm btn-success mt-2" id="activateSaisie">
                <i class="fas fa-check-circle me-1"></i> Activer les saisies
              </a>
              <?php else: ?>
              <button class="btn btn-sm btn-secondary mt-2" disabled>
                <i class="fas fa-clock me-1"></i> Disponible le 18 du mois
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4 mb-md-0">
        <h5>Lydec</h5>
        <p class="mb-3">Votre fournisseur d'électricité et d'eau, engagé pour un service de qualité et un développement durable.</p>
        <div class="d-flex mt-4">
          <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
          <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
          <a href="#" class="me-3"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-md-3 mb-4 mb-md-0">
        <h5>Navigation</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="dashboard.php">Tableau de bord</a></li>
          <li class="mb-2"><a href="clients.php">Clients</a></li>
          <li class="mb-2"><a href="consumption.php">Consommations</a></li>
          <li class="mb-2"><a href="reclamations.php">Réclamations</a></li>
        </ul>
      </div>
      <div class="col-md-5">
        <h5>Contact</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 48, Rue Mohamed Diouri, Casablanca</li>
          <li class="mb-2"><i class="fas fa-phone me-2"></i> 05 22 54 90 00</li>
          <li class="mb-2"><i class="fas fa-envelope me-2"></i> <a href="mailto:service-client@lydec.ma">service-client@lydec.ma</a></li>
          <li class="mb-2"><i class="fas fa-clock me-2"></i> Lun-Ven: 8h00-16h30</li>
        </ul>
      </div>
    </div>
    <div class="border-top border-secondary pt-4 mt-4 text-center">
      <p class="mb-0">&copy; <?php echo date('Y'); ?> - Lydec. Tous droits réservés.</p>
    </div>
  </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- SweetAlert2 for nice alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Initialize AOS animations
  AOS.init({
    duration: 800,
    once: true
  });
  
  // Hide page loader after page loads
  window.addEventListener('load', function() {
    const loader = document.querySelector('.page-loader');
    if (loader) {
      loader.classList.add('hidden');
      setTimeout(() => {
        loader.style.display = 'none';
      }, 500);
    }
  });
  
  // Activation des saisies (if it's the 18th)
  document.addEventListener('DOMContentLoaded', function() {
    const activateBtn = document.getElementById('activateSaisie');
    if (activateBtn) {
      activateBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        Swal.fire({
          title: 'Activer les saisies ?',
          text: 'Cela permettra aux clients de saisir leurs consommations pour ce mois.',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#2B6041',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Oui, activer',
          cancelButtonText: 'Annuler'
        }).then((result) => {
          if (result.isConfirmed) {
            // Send request to activate
            fetch('../../traitement/fournisseurTraitement.php?action=activate_saisie', {
              method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                Swal.fire(
                  'Activé!',
                  'Les clients peuvent maintenant saisir leurs consommations.',
                  'success'
                );
              } else {
                Swal.fire(
                  'Erreur',
                  data.message || 'Une erreur est survenue.',
                  'error'
                );
              }
            })
            .catch(error => {
              console.error('Error:', error);
              Swal.fire(
                'Erreur',
                'Une erreur de connexion est survenue.',
                'error'
              );
            });
          }
        });
      });
    }
  });
</script>

</body>
</html>
