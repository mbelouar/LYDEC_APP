
<?php
session_start();
if (!isset($_SESSION['agent'])) {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Agent - Gestion d'Électricité</title>
  
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
          <a class="nav-link" href="../../traitement/agentTraitement.php?action=logout">
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
    </h1>
  </div>

    <!-- Welcome Banner -->
    <div class="card mb-4" data-aos="fade-up">
        <div class="card-body">
            <div class="row align-items-center">
            <div class="col-md-8">
                <h4>Bienvenue, Agent !</h4>
                <p class="mb-0">Gestion des consommations annuelles des clients</p>
            </div>
                <div class="col-md-4 text-end">
                    <img src="../../uploads/Lydec.png" alt="Welcome" class="img-fluid" style="max-height: 100px;">
                </div>
            </div>
        </div>
    </div>

  <!-- Ajout manuel de consommation -->
    <div class="card shadow mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-plus-circle me-2"></i> Ajouter une Consommation annuelle
            </h6>
        </div>
        <div class="card-body">
            <form id="addAnnualConsumptionForm" class="row g-3">
                <input type="hidden" name="action" value="add_annual">
                
                <div class="col-md-4">
                    <label for="compteur" class="form-label">Numéro Compteur</label>
                    <input type="text" id="compteur" name="compteur" class="form-control" required>
                </div>
                
                <div class="col-md-4">
                    <label for="consommation" class="form-label">Consommation (kWh)</label>
                    <input type="number" id="consommation" name="consommation" class="form-control" required min="0">
                </div>
                
                <div class="col-md-4">
                    <label for="annee" class="form-label">Année</label>
                    <input type="number" id="annee" name="annee" class="form-control" required 
                        min="<?php echo date('Y')-5; ?>" max="<?php echo date('Y'); ?>" 
                        value="<?php echo date('Y'); ?>">
                </div>
                
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                    <button type="button" class="btn btn-success" id="downloadTxtBtn">
                        <i class="fas fa-download me-1"></i> Télécharger fichier
                    </button>
                </div>
            </form>
            
            <div id="consumptionAlert" class="alert mt-4 d-none" role="alert"></div>
        </div>
    </div>
</div>





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
  
  // Handle form submission
  document.getElementById('addAnnualConsumptionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const alert = document.getElementById('consumptionAlert');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...';
    
    fetch('../../traitement/ConsommationAnnuelleTraitement.php', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Erreur réseau');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        alert.className = 'alert alert-success mt-4';
        // Clear form on success
        form.reset();
      } else {
        alert.className = 'alert alert-danger mt-4';
      }
      alert.innerHTML = `<i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>${data.message}`;
      alert.classList.remove('d-none');
    })
    .catch(error => {
      alert.className = 'alert alert-danger mt-4';
      alert.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Erreur de connexion au serveur';
      alert.classList.remove('d-none');
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Enregistrer';
    });
  });

    // Handle TXT download
    document.getElementById('downloadTxtBtn').addEventListener('click', function() {
        const btn = this;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Génération...';
        
        // Open in new tab to avoid navigation issues
        window.open('../../traitement/ConsommationAnnuelleTraitement.php?action=download_txt', '_blank');
        
        // Reset button after delay
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-download me-1"></i> Télécharger fichier';
        }, 2000);
    });

</script>

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

</body>
</html>
