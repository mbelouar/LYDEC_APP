<?php
session_start();
require_once '../BD/db.php';
require_once '../BD/Agent.php';

$agentModel = new Agent($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $emailAgent = $_POST['emailAgent'];
        $passwordAgent = $_POST['passwordAgent'];

        // Verify credentials in Agent table
        $agent = $agentModel->login($emailAgent, $passwordAgent);
        if ($agent) {
            // Store in session
            $_SESSION['agent'] = $agent;
            header("Location: ../IHM/agent/dashboard.php");
            exit;
        } else {
            header("Location: ../IHM/agent/login.php?error=invalid");
            exit;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'logout') {
        session_destroy();
        header("Location: ../IHM/agent/login.php");
        exit;
    }
}
?>