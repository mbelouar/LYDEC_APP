
<?php
session_start();
if (!isset($_SESSION['fournisseur'])) {
    header("Location: login.php");
    exit;
}
require_once '../../BD/db.php';

// Récupérer le nombre exact de clients depuis la base de données
$stmt = $pdo->query("SELECT COUNT(*) AS nbClients FROM Client");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$nbClients = $result['nbClients'];

// Récupérer le nombre de réclamations en attente
$stmt = $pdo->query("SELECT COUNT(*) AS nbReclamations FROM Reclamation WHERE statut = 'en attente'");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$nbReclamations = $result['nbReclamations'] ?? 0;

// Récupérer le nombre de consommations à valider (toutes les consommations récentes)
$stmt = $pdo->query("SELECT COUNT(*) AS nbConsommations FROM Consumption");
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$nbConsommations = $result['nbConsommations'] ?? 0;

// Date du jour actuel
$dateActuelle = date('m/d/Y');
$jourActuel = (int)date('m');
$moisActuel = (int)date('d');
$anneeActuelle = (int)date('Y');

// Vérifier si nous sommes le 18 du mois (pour activation des saisies)
$estJourSaisie = ($jourActuel == 10);
?>
