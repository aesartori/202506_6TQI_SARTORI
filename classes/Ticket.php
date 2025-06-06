<?php
class Ticket {
    private $conn;
    private $table = 'ticket';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lire() {
        $query = "SELECT t.*, e.titre as evenement_titre 
                  FROM " . $this->table . " t
                  JOIN evenement e ON t.id_evenement = e.id_evenement
                  ORDER BY t.date_reservation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lireUn($id) {
        $query = "SELECT t.*, e.titre as evenement_titre 
                  FROM " . $this->table . " t
                  JOIN evenement e ON t.id_evenement = e.id_evenement
                  WHERE t.id_ticket = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function lireParEvenement($evenement_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_evenement = :evenement_id 
                  ORDER BY date_reservation ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':evenement_id', $evenement_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function genererCodeUnique() {
        do {
            $code = 'TCK-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
            $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE code_unique = :code";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            $existe = $stmt->fetchColumn() > 0;
        } while ($existe);
        
        return $code;
    }

    public function creer($id_evenement, $nom_complet, $email, $quantite, $prix_personne) {
        $code = $this->genererCodeUnique();
        $prix_total = $quantite * $prix_personne;
        
        $query = "INSERT INTO " . $this->table . " 
                  (code_unique, nom_complet, email, quantite, prix_personne, prix_total, id_evenement) 
                  VALUES (:code, :nom_complet, :email, :quantite, :prix_personne, :prix_total, :id_evenement)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':nom_complet', $nom_complet);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':prix_personne', $prix_personne);
        $stmt->bindParam(':prix_total', $prix_total);
        $stmt->bindParam(':id_evenement', $id_evenement);
        
        return $stmt->execute();
    }

    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_ticket = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function marquerUtilise($id) {
        $query = "UPDATE " . $this->table . " SET utilise = 1 WHERE id_ticket = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function compterParEvenement($evenement_id) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE id_evenement = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $evenement_id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getStatistiques() {
        $query = "SELECT 
                    COUNT(*) as total_tickets,
                    COUNT(CASE WHEN statut = 'Payé' THEN 1 END) as tickets_payes,
                    SUM(CASE WHEN statut = 'Payé' THEN prix_total ELSE 0 END) as revenus_total
                  FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
