<?php
// BD/Client.php

class Client {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Inscription d'un client avec vérification de l'email
    public function register($data) {
        // Vérifier si l'email existe déjà
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as nb FROM Client WHERE email = ?");
        $stmt->execute([$data['email']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['nb'] > 0) {
            return false;
        }
    
        $sql = "INSERT INTO Client (cin, nom, prenom, email, telephone, adresse, numero_de_compteur, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['cin'],
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['telephone'],
            $data['adresse'],
            $data['numero_de_compteur'],
            password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }
    
    public function getAllClients() {
        $sql = "SELECT * FROM Client";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Connexion d'un client
    public function login($email, $password) {
        $sql = "SELECT * FROM Client WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client && password_verify($password, $client['password'])) {
            return $client;
        }
        return false;
    }

    // Récupérer le profil d'un client
    public function getProfile($id) {
        $sql = "SELECT * FROM Client WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mise à jour du profil d'un client (pour le client lui-même)
    public function updateProfile($id, $data) {
        $sql = "UPDATE Client SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, numero_de_compteur = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['telephone'],
            $data['adresse'],
            $data['numero_de_compteur'],
            $id
        ]);
    }

    // Mise à jour d'un client (gestion par le fournisseur)
    public function updateClient($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $sql = "UPDATE Client SET cin = ?, nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, numero_de_compteur = ?, password = ?
                    WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['cin'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['telephone'],
                $data['adresse'],
                $data['numero_de_compteur'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $id
            ]);
        } else {
            $sql = "UPDATE Client SET cin = ?, nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?, numero_de_compteur = ?
                    WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['cin'],
                $data['nom'],
                $data['prenom'],
                $data['email'],
                $data['telephone'],
                $data['adresse'],
                $data['numero_de_compteur'],
                $id
            ]);
        }
    }

    // Suppression d'un client (gestion par le fournisseur)
    public function deleteClient($id) {
        $sql = "DELETE FROM Client WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
