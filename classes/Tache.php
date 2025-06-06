<?php
class Tache {
    private $conn;
    private $table = 'tache';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lire() {
        $query = "SELECT t.*, e.nom as evenement_nom FROM " . $this->table . " t 
                  LEFT JOIN evenement e ON t.evenement_id = e.id 
                  ORDER BY t.date_echeance ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lireUn($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function creer($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (titre, description, date_echeance, statut, priorite, evenement_id) 
                  VALUES (:titre, :description, :date_echeance, :statut, :priorite, :evenement_id)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':date_echeance', $data['date_echeance']);
        $stmt->bindParam(':statut', $data['statut']);
        $stmt->bindParam(':priorite', $data['priorite']);
        $stmt->bindParam(':evenement_id', $data['evenement_id']);
        
        return $stmt->execute();
    }

    public function modifier($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET titre=:titre, description=:description, date_echeance=:date_echeance, 
                      statut=:statut, priorite=:priorite, evenement_id=:evenement_id 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':date_echeance', $data['date_echeance']);
        $stmt->bindParam(':statut', $data['statut']);
        $stmt->bindParam(':priorite', $data['priorite']);
        $stmt->bindParam(':evenement_id', $data['evenement_id']);
        
        return $stmt->execute();
    }

    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>