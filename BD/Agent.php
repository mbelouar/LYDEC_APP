<?php
class Agent {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($emailAgent, $passwordAgent) {
        $stmt = $this->pdo->prepare("SELECT * FROM Agent WHERE emailAgent = ?");
        $stmt->execute([$emailAgent]);
        $agent = $stmt->fetch();

        if ($agent && password_verify($passwordAgent, $agent['passwordAgent'])) {
            return $agent;
        }
        return false;
    }

    public function addAgent($nomAgent, $prenomAgent, $emailAgent, $passwordAgent, $roleAgent, $fournisseurId) {
        $sql = "INSERT INTO Agent (nomAgent, prenomAgent, emailAgent, passwordAgent, roleAgent, fournisseur_id)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $nomAgent,
            $prenomAgent,
            $emailAgent,
            password_hash($passwordAgent, PASSWORD_BCRYPT),
            $roleAgent,
            $fournisseurId
        ]);
    }

    public function getAgentsByFournisseur($fournisseurId) {
        $sql = "SELECT * FROM Agent WHERE fournisseur_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fournisseurId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>