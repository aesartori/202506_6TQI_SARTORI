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
    $photo = $artisteData['photo'];
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        !is_dir($uploadDir) && mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . $_FILES['photo']['name'];
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $fileName)) {
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

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Modifier un artiste</h2>
        <a href="artistes.php" class="btn btn-secondary btn-action">
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
            <h5 class="mb-0">Informations de l'artiste</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Nom de l'artiste *</label>
                    <input type="text" name="nom" class="form-control" 
                           value="<?= htmlspecialchars($artisteData['nom']) ?>" required
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">URL (site web, page, profil)</label>
                    <input type="url" name="url" class="form-control" 
                           value="<?= htmlspecialchars($artisteData['url']) ?>"
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Changer la photo (optionnel)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*"
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                    <small style="color: var(--text-secondary);">Laissez vide pour conserver la photo actuelle</small>
                </div>

                <?php if ($artisteData['photo']): ?>
                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Photo actuelle :</label>
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($artisteData['photo']) ?>" 
                             alt="Photo actuelle" class="img-thumbnail" style="max-width: 200px; border-color: #444;">
                    </div>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="artistes.php" class="btn btn-secondary btn-action">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning btn-action">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
