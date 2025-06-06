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
    $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'specialite' => $_POST['specialite'],
        'email' => $_POST['email'],
        'telephone' => $_POST['telephone'],
        'bio' => $_POST['bio']
    ];
    
    if ($artiste->modifier($id, $data)) {
        header("Location: artistes.php?success=modification");
        exit();
    } else {
        $error = "Erreur lors de la modification";
    }
}

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-user-edit"></i> Modifier l'artiste</h1>
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
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="nom" 
                               value="<?= htmlspecialchars($artisteData['nom']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Prénom *</label>
                        <input type="text" class="form-control" name="prenom" 
                               value="<?= htmlspecialchars($artisteData['prenom']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Spécialité *</label>
                <input type="text" class="form-control" name="specialite" 
                       value="<?= htmlspecialchars($artisteData['specialite']) ?>" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= htmlspecialchars($artisteData['email']) ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" name="telephone" 
                               value="<?= htmlspecialchars($artisteData['telephone']) ?>">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Biographie</label>
                <textarea class="form-control" name="bio" rows="4"><?= htmlspecialchars($artisteData['bio']) ?></textarea>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="artistes.php" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Modifier l'artiste
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>