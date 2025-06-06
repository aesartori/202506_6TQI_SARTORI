<?php
class Artiste {
    private $conn;
    private $table = 'artiste';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lire() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nom, prenom ASC";
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
                  (nom, prenom, specialite, email, telephone, bio) 
                  VALUES (:nom, :prenom, :specialite, :email, :telephone, :bio)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':specialite', $data['specialite']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':bio', $data['bio']);
        
        return $stmt->execute();
    }

    public function modifier($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET nom=:nom, prenom=:prenom, specialite=:specialite, 
                      email=:email, telephone=:telephone, bio=:bio 
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':specialite', $data['specialite']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telephone', $data['telephone']);
        $stmt->bindParam(':bio', $data['bio']);
        
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