<?php
class Evenement {
    private $conn;
    private $table = 'evenement';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lire() {
        $query = "SELECT e.*, v.nom as venue_nom, a.nom as artiste_nom 
                  FROM " . $this->table . " e 
                  LEFT JOIN venue v ON e.id_venue = v.id_venue 
                  LEFT JOIN artiste a ON e.id_artiste = a.id_artiste 
                  ORDER BY e.date_heure ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lireUn($id) {
        $query = "SELECT e.*, v.nom as venue_nom, a.nom as artiste_nom 
                  FROM " . $this->table . " e 
                  LEFT JOIN venue v ON e.id_venue = v.id_venue 
                  LEFT JOIN artiste a ON e.id_artiste = a.id_artiste 
                  WHERE e.id_evenement = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function creer($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (titre, description, date_heure, prix, id_venue, id_artiste, image) 
                  VALUES (:titre, :description, :date_heure, :prix, :id_venue, :id_artiste, :image)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':date_heure', $data['date_heure']);
        $stmt->bindParam(':prix', $data['prix']);
        $stmt->bindParam(':id_venue', $data['id_venue']);
        $stmt->bindParam(':id_artiste', $data['id_artiste']);
        $stmt->bindParam(':image', $data['image']);
        
        return $stmt->execute();
    }

    public function modifier($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET titre = :titre, 
                      description = :description, 
                      date_heure = :date_heure, 
                      prix = :prix, 
                      id_venue = :id_venue, 
                      id_artiste = :id_artiste";
        
        // Ajout conditionnel de l'image si fournie
        if(isset($data['image']) && !empty($data['image'])) {
            $query .= ", image = :image";
        }
        
        $query .= " WHERE id_evenement = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':date_heure', $data['date_heure']);
        $stmt->bindParam(':prix', $data['prix']);
        $stmt->bindParam(':id_venue', $data['id_venue']);
        $stmt->bindParam(':id_artiste', $data['id_artiste']);
        
        if(isset($data['image']) && !empty($data['image'])) {
            $stmt->bindParam(':image', $data['image']);
        }
        
        return $stmt->execute();
    }

    public function supprimer($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_evenement = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getProchains($limit = 6) {
        $query = "SELECT e.*, v.nom as venue_nom, a.nom as artiste_nom 
                  FROM " . $this->table . " e 
                  LEFT JOIN venue v ON e.id_venue = v.id_venue 
                  LEFT JOIN artiste a ON e.id_artiste = a.id_artiste 
                  WHERE e.date_heure > NOW()
                  ORDER BY e.date_heure ASC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lireParLieu($id_venue) {
    $query = "SELECT * FROM evenement WHERE id_venue = :id_venue";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_venue', $id_venue, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lireParEvenement($id_evenement) {
        $query = "SELECT * FROM ticket WHERE id_evenement = :id_evenement";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_evenement', $id_evenement, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function lireParArtiste($id_artiste) {
        $query = "SELECT * FROM evenement WHERE id_artiste = :id_artiste";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_artiste', $id_artiste, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
