<?php
class Evenement {
    private $conn;
    private $table = 'evenement';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lire() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY date_debut ASC";
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
                  (nom, description, date_debut, date_fin, lieu, capacite, prix, statut) 
                  VALUES (:nom, :description, :date_debut, :date_fin, :lieu, :capacite, :prix, :statut)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':date_debut', $data['date_debut']);
        $stmt->bindParam(':date_fin', $data['date_fin']);
        $stmt->bindParam(':lieu', $data['lieu']);
        $stmt->bindParam(':capacite', $data['capacite']);
        $stmt->bindParam(':prix', $data['prix']);
        $stmt->bindParam(':statut', $data['statut']);
        
        return $stmt->execute();
    }

    public function modifier($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nom=:nom, description=:description, date_debut=:date_debut, 
                      date_fin=:date_fin, lieu=:lieu, capacite=:capacite, prix=:prix, statut=:statut 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':date_debut', $data['date_debut']);
        $stmt->bindParam(':date_fin', $data['date_fin']);
        $stmt->bindParam(':lieu', $data['lieu']);
        $stmt->bindParam(':capacite', $data['capacite']);
        $stmt->bindParam(':prix', $data['prix']);
        $stmt->bindParam(':statut', $data['statut']);
        
        return $stmt->execute();
    }

    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getArtistes($evenement_id) {
        $query = "SELECT a.*, ea.role FROM artiste a 
                  JOIN evenement_artiste ea ON a.id = ea.artiste_id 
                  WHERE ea.evenement_id = :evenement_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':evenement_id', $evenement_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>