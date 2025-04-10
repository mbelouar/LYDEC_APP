<?php
session_start();
require_once '../BD/db.php';
require_once '../BD/ConsommationAnnuelle.php';

$consommationModel = new ConsommationAnnuelle($pdo);


$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'add_annual':
        handleAddConsommation();
        break;
    case 'download_txt':
        handleDownloadTxt();
        break;
    default:
        sendResponse(false, 'Action non reconnue');
}

function handleAddConsommation() {
    global $consommationModel;

    $compteur = trim($_POST['compteur'] ?? '');
    $consommation = $_POST['consommation'] ?? 0;
    $annee = $_POST['annee'] ?? date('Y');

    if (empty($compteur)) {
        sendResponse(false, 'Le numéro de compteur est requis');
    }

    if (!is_numeric($consommation) || $consommation < 0) {
        sendResponse(false, 'La consommation doit être un nombre positif');
    }

    if (!is_numeric($annee) || $annee < 2000 || $annee > date('Y')) {
        sendResponse(false, 'Année invalide');
    }


    try {
        $success = $consommationModel->addConsommationAnnuelle(
            $compteur,
            $consommation,
            $annee
        );

        if ($success) {
            sendResponse(true, 'Consommation enregistrée avec succès');
        } else {
            sendResponse(false, 'Erreur lors de l\'enregistrement');
        }
    } catch (PDOException $e) {
        sendResponse(false, 'Erreur de base de données: ' . $e->getMessage());
    }
}


function handleDownloadTxt() {
    global $consommationModel;
    
    $consommations = $consommationModel->getAllConsommationsForTxt();
    
    $content = "==================================================================\n";
    $content .= "           CONSOMMATION ANNUELLE AGENT vs CLIENT\n";
    $content .= "==================================================================\n\n";
    
    // Header
    $content .= str_pad("CLIENT", 25);
    $content .= str_pad("ANNÉE", 10);
    $content .= str_pad("AGENT (kWh)", 15);
    $content .= str_pad("CLIENT (kWh)", 15);
    $content .= "ÉCART\n";
    $content .= str_repeat("-", 80) . "\n";
    
    // Data rows
    foreach ($consommations as $row) {
        $client = $row['client_nom'] . ' ' . $row['client_prenom'];
        $content .= str_pad(substr($client, 0, 23), 25);
        $content .= str_pad($row['annee'], 10);
        $content .= str_pad(number_format($row['consommation_agent'], 0, ',', ' '), 15);
        $content .= str_pad(number_format($row['consommation_declaree'], 0, ',', ' '), 15);
        
        // Calcul de l’écart
        $ecart = $row['ecart'];
        $content .= sprintf("%+d", $ecart);
        
        // Ajout d’un "!" si l’agent dépasse le client de plus de 50 kWh
        if (
            $row['consommation_agent'] > $row['consommation_declaree'] &&
            $ecart > 50
        ) {
            $content .= " !";
        }

        $content .= "\n";
    }
    
    // Footer
    $content .= "\n==================================================================\n";
    $content .= "LÉGENDE:\n";
    $content .= "! = consommation agent dépasse de plus de 50 kWh la déclaration du client\n";
    $content .= "Écart positif = consommation agent > déclaration client\n";
    $content .= "Écart négatif = consommation agent < déclaration client\n";
    $content .= "Généré le: " . date('d/m/Y H:i') . "\n";
    
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="Comparaison_consommations_' . date('Y-m-d') . '.txt"');
    echo $content;
    exit;
}


function sendResponse($success, $message) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}
?>