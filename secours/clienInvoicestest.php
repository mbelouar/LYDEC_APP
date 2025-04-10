<?php
session_start();

// Vérifier si le client est connecté
if (!isset($_SESSION['client'])) {
    header("Location: connexion.php");
    exit;
}

require_once '../../BD/db.php';
require_once '../../BD/Facture.php';

// Récupérer les informations du client connecté
$clientId = $_SESSION['client']['id'];
$factureModel = new Facture($pdo);

// Récupérer toutes les factures du client connecté
$factures = $factureModel->getFacturesByClient($clientId);

// Calculer les statistiques
$totalInvoices = count($factures);
$totalPaid = 0;
$totalUnpaid = 0;
$totalAmount = 0;
$totalDue = 0;

foreach ($factures as $facture) {
    $totalAmount += $facture['montant'];
    if ($facture['statut'] === 'payée') {
        $totalPaid++;
    } else {
        $totalUnpaid++;
        $totalDue += $facture['montant'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Factures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="h3 mb-4">Mes Factures</h1>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Factures Totales</h5>
                    <p class="card-text"><?php echo $totalInvoices; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Factures Payées</h5>
                    <p class="card-text"><?php echo $totalPaid; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Factures Impayées</h5>
                    <p class="card-text"><?php echo $totalUnpaid; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Montant Total Dû</h5>
                    <p class="card-text"><?php echo number_format($totalDue, 2); ?> DH</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des factures -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($factures)): ?>
                    <?php foreach ($factures as $facture): ?>
                        <tr>
                            <td>#<?php echo $facture['id']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($facture['date_emission'])); ?></td>
                            <td><?php echo number_format($facture['montant'], 2); ?> DH</td>
                            <td>
                                <?php if ($facture['statut'] === 'payée'): ?>
                                    <span class="badge bg-success">Payée</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Impayée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="../../traitement/factureTraitement.php?action=detail&id=<?php echo $facture['id']; ?>" class="btn btn-sm btn-info">Détails</a>
                                <?php if ($facture['statut'] !== 'payée'): ?>
                                    <form action="../../traitement/factureTraitement.php?action=pay" method="POST" style="display:inline;">
                                        <input type="hidden" name="facture_id" value="<?php echo $facture['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Payer</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucune facture trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
