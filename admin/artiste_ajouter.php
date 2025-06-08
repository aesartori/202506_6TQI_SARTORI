<?php
require_once '../config/database.php';
require_once '../classes/Artiste.php';

$database = new Database();
$db = $database->getConnection();
$artiste = new Artiste($db);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photo = null;
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        !is_dir($uploadDir) && mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . $_FILES['photo']['name'];
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $fileName)) {
            $photo = $fileName;
        }
    }
    
    if ($artiste->creer($_POST['nom'], $_POST['url'], $photo)) {
        header("Location: artistes.php?success=creation");
        exit();
    } else {
        $error = "Erreur lors de l'ajout de l'artiste";
    }
}

include 'includes/header.php';
?>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus"></i> Ajouter un artiste</h2>
        <a href="artistes.php" class="btn btn-secondary btn-action">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger" style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
        <?= $error ?>
    </div>
    <?php endif; ?>

    <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
        <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
            <h5 class="mb-0">Informations du nouvel artiste</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Nom de l'artiste *</label>
                    <input type="text" name="nom" class="form-control" required 
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">URL (site web, page, profil)</label>
                    <input type="url" name="url" class="form-control" 
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Photo (optionnel)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" 
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="artistes.php" class="btn btn-secondary btn-action">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success btn-action">
                        <i class="fas fa-plus"></i> Ajouter l'artiste
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
