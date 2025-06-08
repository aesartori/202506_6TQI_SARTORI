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
    $photo = $venueData['photo'];
    
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

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Modifier un lieu</h2>
        <a href="lieux.php" class="btn btn-secondary btn-action">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <?php if (isset($error)): ?>
    <div class="alert alert-danger" style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
        <?= $error ?>
    </div>
    <?php endif; ?>

    <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
        <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
            <h5 class="mb-0">Informations du lieu</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Nom du lieu *</label>
                            <input type="text" name="nom" class="form-control" 
                                   value="<?= htmlspecialchars($venueData['nom']) ?>" required
                                   style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Type de lieu</label>
                            <select name="type" class="form-select"
                                    style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
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
                    <label class="form-label" style="color: var(--text-primary);">Adresse</label>
                    <input type="text" name="adresse" class="form-control" 
                           value="<?= htmlspecialchars($venueData['adresse']) ?>"
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">URL du site (optionnel)</label>
                    <input type="url" name="url" class="form-control" 
                           value="<?= htmlspecialchars($venueData['url']) ?>"
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Changer la photo (optionnel)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*"
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                    <small style="color: var(--text-secondary);">Laissez vide pour conserver la photo actuelle.</small>
                </div>

                <?php if ($venueData['photo']): ?>
                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Photo actuelle :</label>
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($venueData['photo']) ?>" 
                             alt="Photo actuelle" class="img-thumbnail" 
                             style="max-width: 200px; border-color: #444;">
                    </div>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="lieux.php" class="btn btn-secondary btn-action">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning btn-action">
                        <i class="fas fa-save"></i> Modifier le lieu
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
