<?php
require_once '../config/database.php';
require_once '../classes/Artiste.php';

$database = new Database();
$db = $database->getConnection();
$artiste = new Artiste($db);

$id = $_GET['id'] ?? 0;
$artisteData = $artiste->lireUn($id);

if (!$artisteData) {
    header("Location: artistes.php?error=not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photo = $artisteData['photo']; // Garder l'ancienne photo par défaut
    
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
    
    if ($artiste->modifier($id, $_POST['nom'], $_POST['url'], $photo)) {
        header("Location: artistes.php?success=modification");
        exit();
    } else {
        $error = "Erreur lors de la modification";
    }
}

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Modifier un artiste</h2>
        <a href="artistes.php" class="btn btn-secondary">
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
            <h5 class="mb-0">Informations de l'artiste</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nom de l'artiste *</label>
                    <input type="text" name="nom" class="form-control" 
                           value="<?= htmlspecialchars($artisteData['nom']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">URL (site web, page, profil)</label>
                    <input type="url" name="url" class="form-control" 
                           value="<?= htmlspecialchars($artisteData['url']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Changer la photo (optionnel)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    <small class="text-muted">Laissez vide pour conserver la photo actuelle.</small>
                </div>

                <?php if ($artisteData['photo']): ?>
                <div class="mb-3">
                    <label class="form-label">Photo actuelle :</label>
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($artisteData['photo']) ?>" 
                             alt="Photo actuelle" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="artistes.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Modifier l'artiste
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
