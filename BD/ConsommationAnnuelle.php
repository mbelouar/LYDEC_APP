<?php
class ConsommationAnnuelle {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function addConsommationAnnuelle($numero_de_compteur, $totalConso, $annee) {
        $clientId = $this->getClientIdByNumeroCompteur($numero_de_compteur);
        
        if (!$clientId) {
            throw new Exception("Client avec numero de compteur $numero_de_compteur non trouvé");
        }
    
        // Calculate sum of monthly consumptions
        $sumMonthly = $this->getSumMonthlyConsumption($clientId, $annee);
        
        // Calculate the difference (ecart)
        $ecart = $totalConso - $sumMonthly;
    
        $sql = "INSERT INTO ConsommationAnnuelle (annee, totalConso, client_id, ecart) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$annee, $totalConso, $clientId, $ecart]);
    }
    
    /**
     * Get sum of monthly consumptions for a client in a specific year
     */
    private function getSumMonthlyConsumption($clientId, $annee) {
        $sql = "SELECT SUM(current_reading) as total 
                FROM Consumption 
                WHERE client_id = ? 
                AND status = 'approved'
                AND strftime('%Y', dateReleve) = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$clientId, $annee]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }


    public function getAllConsommationsForTxt() {
        $sql = "SELECT 
                    c.numero_de_compteur AS numero_compteur,
                    cl.nom AS client_nom,
                    cl.prenom AS client_prenom,
                    ca.annee,
                    ca.totalConso AS consommation_agent,
                    (
                        SELECT current_reading
                        FROM Consumption 
                        WHERE client_id = ca.client_id 
                        AND month LIKE '%Décembre%'
                        AND strftime('%Y', dateReleve) = ca.annee
                        AND status = 'approved'
                        ORDER BY dateReleve DESC
                        LIMIT 1
                    ) AS consommation_declaree,
                    ca.totalConso - IFNULL((
                        SELECT current_reading
                        FROM Consumption 
                        WHERE client_id = ca.client_id 
                        AND month LIKE '%Décembre%'
                        AND strftime('%Y', dateReleve) = ca.annee
                        AND status = 'approved'
                        ORDER BY dateReleve DESC
                        LIMIT 1
                    ), 0) AS ecart
                FROM ConsommationAnnuelle ca
                JOIN Client c ON ca.client_id = c.id
                JOIN Client cl ON ca.client_id = cl.id
                ORDER BY ca.annee DESC, c.numero_de_compteur";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    


    private function getClientIdByNumeroCompteur($numero_de_compteur) {
        $sql = "SELECT id FROM Client WHERE numero_de_compteur = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$numero_de_compteur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
}
?>