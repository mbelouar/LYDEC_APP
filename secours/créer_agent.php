<?php
require_once __DIR__ . '/BD/db.php';      
require_once __DIR__ . '/BD/Agent.php';      
$agentModel = new Agent($pdo);

// Test agent data
$testAgent = [
    'nomAgent' => 'MOUNA',
    'prenomAgent' => 'Agent',
    'emailAgent' => 'mouna@gmail.com',
    'passwordAgent' => 'mouna123',
    'roleAgent' => 'technicien',
    'fournisseur_id' => 2  
];

if ($agentModel->addAgent(
    $testAgent['nomAgent'],
    $testAgent['prenomAgent'],
    $testAgent['emailAgent'],
    $testAgent['passwordAgent'],
    $testAgent['roleAgent'],
    $testAgent['fournisseur_id']
)) {
    echo "Test agent created successfully!<br>";
    echo "Email: " . $testAgent['emailAgent'] . "<br>";
    echo "Password: " . $testAgent['passwordAgent'];

} else {
    echo "Failed to create test agent. Check:";
    echo "<ul>";
    echo "<li>Does fournisseur_id=1 exist?</li>";
    echo "<li>Is emailAgent unique?</li>";
    echo "<li>Database connection working?</li>";
    echo "</ul>";
}
?>