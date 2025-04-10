<?php
session_start();
require_once '../../BD/db.php';
require_once '../../BD/Reclamation.php';

// Set page variables
$pageTitle = 'Réclamations';
$activePage = 'complaint';

// Check if the user is logged in
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

$reclamationModel = new Reclamation($pdo);
$clientId = $_SESSION['client']['id'];

// Récupérer les réclamations du client
$reclamations = $reclamationModel->getReclamationsByClient($clientId);

// Page-specific CSS for any custom styling
$pageSpecificCSS = '<style>
  .complaint-card {
    transition: transform 0.3s;
  }
  .complaint-card:hover {
    transform: translateY(-5px);
  }
</style>';

// Start page content
ob_start();
?>