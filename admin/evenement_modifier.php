<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';
require_once '../classes/Venue.php';
require_once '../classes/Artiste.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);
$venue = new Venue($db);
$artiste = new Artiste($db);

$id = $_GET['id'] ?? 0;
$eventData = $evenement->lireUn($id);

if (!$eventData) {
    header("Location: evenements.php?error=not_found");
    exit();
}

// Récupérer les lieux et artistes pour les listes déroulantes
$venues = $venue->lire();
$artistes = $artiste->lire();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $eventData['image'] ?? null; // Garder l'ancienne image par défaut
    
    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . $_FILES['image']['name'];
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            $image = $fileName;
        }
    }
    
    $data = [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'date_heure' => $_POST['date_heure'],
        'id_venue' => $_POST['id_venue'],
        'id_artiste' => $_POST['id_artiste'] ?? null,
        'prix' => $_POST['prix'] ?: 0,
        'image' => $image
    ];
    
    if ($evenement->modifier($id, $data)) {
        header("Location: evenements.php?success=modification");
        exit();
    } else {
        $error = "Erreur lors de la modification";
    }
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Modifier l'événement</h2>
        <a href="evenements.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?= $error ?>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Informations de l'événement</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Titre de l'événement</label>
                    <input type="text" name="titre" class="form-control" 
                           value="<?= htmlspecialchars($eventData['titre']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($eventData['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date et heure</label>
                    <input type="datetime-local" name="date_heure" class="form-control" 
                           value="<?= date('Y-m-d\TH:i', strtotime($eventData['date_heure'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lieu (venue)</label>
                    <select name="id_venue" class="form-select" required>
                        <option value="">Sélectionner un lieu</option>
                        <?php foreach ($venues as $v): ?>
                        <option value="<?= $v['id_venue'] ?>" <?= ($eventData['id_venue'] == $v['id_venue']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Artiste</label>
                    <select name="id_artiste" class="form-select">
                        <option value="">Sélectionner un artiste</option>
                        <?php foreach ($artistes as $art): ?>
                        <option value="<?= $art['id_artiste'] ?>" <?= ($eventData['id_artiste'] == $art['id_artiste']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($art['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prix (€)</label>
                    <div class="input-group">
                        <input type="number" name="prix" class="form-control" step="0.01" min="0" 
                               value="<?= $eventData['prix'] ?>">
                        <span class="input-group-text">€</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Changer l'image (optionnel)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Laissez vide pour conserver l'image actuelle.</small>
                </div>

                <?php if (!empty($eventData['image'])): ?>
                <div class="mb-3">
                    <label class="form-label">Image actuelle :</label>
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($eventData['image']) ?>" 
                             alt="Image actuelle" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="evenements.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
