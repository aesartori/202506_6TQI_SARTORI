<?php
require_once '../config/database.php';
require_once '../classes/Venue.php';

$database = new Database();
$db = $database->getConnection();
$venue = new Venue($db);

$id = $_GET['id'] ?? 0;
$venueData = $venue->lireUn($id);

if (!$venueData) {
    header("Location: lieux.php?error=not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photo = $venueData['photo']; // Garder l'ancienne photo par défaut
    
    // Gestion de l'upload de photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = time() . '_' . $_FILES['photo']['name'];
        $uploadPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $photo = $fileName;
        }
    }
    
    if ($venue->modifier($id, $_POST['nom'], $_POST['type'], $_POST['adresse'], $_POST['url'], $photo)) {
        header("Location: lieux.php?success=modification");
        exit();
    } else {
        $error = "Erreur lors de la modification";
    }
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Modifier un lieu</h2>
        <a href="lieux.php" class="btn btn-secondary">
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
            <h5 class="mb-0">Informations du lieu</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nom du lieu *</label>
                            <input type="text" name="nom" class="form-control" 
                                   value="<?= htmlspecialchars($venueData['nom']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type de lieu</label>
                            <select name="type" class="form-select">
                                <option value="">Sélectionner un type</option>
                                <option value="Salle de concert" <?= $venueData['type'] === 'Salle de concert' ? 'selected' : '' ?>>Salle de concert</option>
                                <option value="Théâtre" <?= $venueData['type'] === 'Théâtre' ? 'selected' : '' ?>>Théâtre</option>
                                <option value="Centre culturel" <?= $venueData['type'] === 'Centre culturel' ? 'selected' : '' ?>>Centre culturel</option>
                                <option value="Hall événementiel" <?= $venueData['type'] === 'Hall événementiel' ? 'selected' : '' ?>>Hall événementiel</option>
                                <option value="Lieu en plein air" <?= $venueData['type'] === 'Lieu en plein air' ? 'selected' : '' ?>>Lieu en plein air</option>
                                <option value="Grande salle" <?= $venueData['type'] === 'Grande salle' ? 'selected' : '' ?>>Grande salle</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" 
                           value="<?= htmlspecialchars($venueData['adresse']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">URL du site (optionnel)</label>
                    <input type="url" name="url" class="form-control" 
                           value="<?= htmlspecialchars($venueData['url']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Changer la photo (optionnel)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    <small class="text-muted">Laissez vide pour conserver la photo actuelle.</small>
                </div>

                <?php if ($venueData['photo']): ?>
                <div class="mb-3">
                    <label class="form-label">Photo actuelle :</label>
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($venueData['photo']) ?>" 
                             alt="Photo actuelle" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="lieux.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Modifier le lieu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
