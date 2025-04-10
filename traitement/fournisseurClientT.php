<?php
session_start();

// Define API_REQUEST constant to prevent any text output from db.php in AJAX calls
define('API_REQUEST', true);

if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}
require_once '../../BD/db.php';

// Si nous sommes en mode édition, récupérons les données du client à modifier
$editingClient = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM Client WHERE id = ?");
    $stmt->execute([$id]);
    $editingClient = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération de la liste de tous les clients pour affichage
$stmt = $pdo->query("SELECT * FROM Client");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Page title
$pageTitle = "Gestion des Clients";
?>
