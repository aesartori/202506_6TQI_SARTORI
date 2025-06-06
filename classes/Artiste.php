<?php
class Artiste {
    private $conn;
    private $table = 'artiste';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lire() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nom ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lireUn($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_artiste = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function creer($nom, $url = null, $photo = null) {
        $query = "INSERT INTO " . $this->table . " (nom, url, photo) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nom, $url, $photo]);
    }

    public function modifier($id, $nom, $url = null, $photo = null) {
        $query = "UPDATE " . $this->table . " SET nom = ?, url = ?, photo = ? WHERE id_artiste = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nom, $url, $photo, $id]);
    }

    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_artiste = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
