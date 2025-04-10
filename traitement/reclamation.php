<?php
session_start();
if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}
require_once '../../BD/db.php';
require_once '../../BD/Reclamation.php';

// Récupérer les réclamations
$reclamationModel = new Reclamation($pdo);
$reclamations = $reclamationModel->getAllReclamations();

// Page title
$pageTitle = "Gestion des Réclamations";
?>