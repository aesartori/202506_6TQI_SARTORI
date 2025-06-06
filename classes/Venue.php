<?php
class Venue {
    private $conn;
    private $table = 'venue';

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
        $query = "SELECT * FROM " . $this->table . " WHERE id_venue = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function creer($nom, $type, $adresse, $url = null, $photo = null) {
        $query = "INSERT INTO " . $this->table . " (nom, type, adresse, url, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nom, $type, $adresse, $url, $photo]);
    }

    public function modifier($id, $nom, $type, $adresse, $url = null, $photo = null) {
        $query = "UPDATE " . $this->table . " SET nom = ?, type = ?, adresse = ?, url = ?, photo = ? WHERE id_venue = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nom, $type, $adresse, $url, $photo, $id]);
    }

    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_venue = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>
