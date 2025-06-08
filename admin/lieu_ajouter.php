<?php
require_once '../config/database.php';
require_once '../classes/Venue.php';

$database = new Database();
$db = $database->getConnection();
$venue = new Venue($db);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photo = null;
    
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
    
    if ($venue->creer($_POST['nom'], $_POST['type'], $_POST['adresse'], $_POST['url'], $photo)) {
        header("Location: lieux.php?success=creation");
        exit();
    } else {
        $error = "Erreur lors de l'ajout du lieu";
    }
}

include 'includes/header.php';
?>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus"></i> Ajouter un lieu</h2>
        <a href="lieux.php" class="btn btn-secondary btn-action">
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
            <h5 class="mb-0">Informations du nouveau lieu</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Nom du lieu *</label>
                            <input type="text" name="nom" class="form-control" required 
                                   style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Type de lieu</label>
                            <select name="type" class="form-select" 
                                    style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                                <option value="">Sélectionner un type</option>
                                <option value="Salle de concert">Salle de concert</option>
                                <option value="Théâtre">Théâtre</option>
                                <option value="Centre culturel">Centre culturel</option>
                                <option value="Hall événementiel">Hall événementiel</option>
                                <option value="Lieu en plein air">Lieu en plein air</option>
                                <option value="Grande salle">Grande salle</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Adresse</label>
                    <input type="text" name="adresse" class="form-control" 
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">URL du site (optionnel)</label>
                    <input type="url" name="url" class="form-control" 
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Photo (optionnel)</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" 
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="lieux.php" class="btn btn-secondary btn-action">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success btn-action">
                        <i class="fas fa-plus"></i> Ajouter le lieu
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
