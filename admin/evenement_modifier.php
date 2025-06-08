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

$venues = $venue->lire();
$artistes = $artiste->lire();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $eventData['image'] ?? null;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        !is_dir($uploadDir) && mkdir($uploadDir, 0777, true);
        
        $fileName = time() . '_' . $_FILES['image']['name'];
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
            $image = $fileName;
        }
    }
    
    if ($evenement->modifier($id, [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'date_heure' => $_POST['date_heure'],
        'id_venue' => $_POST['id_venue'],
        'id_artiste' => $_POST['id_artiste'] ?? null,
        'prix' => $_POST['prix'] ?: 0,
        'image' => $image
    ])) {
        header("Location: evenements.php?success=modification");
        exit();
    } else {
        $error = "Erreur lors de la modification";
    }
}

include 'includes/header.php';
?>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-edit"></i> Modifier l'événement</h2>
        <a href="evenements.php" class="btn btn-secondary btn-action">
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
            <h5 class="mb-0">Informations de l'événement</h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Titre *</label>
                    <input type="text" name="titre" class="form-control" 
                           value="<?= htmlspecialchars($eventData['titre']) ?>" required
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Description</label>
                    <textarea name="description" class="form-control" rows="4"
                              style="background: #2a2a3e; border-color: #444; color: var(--text-primary);"><?= htmlspecialchars($eventData['description']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Date et heure *</label>
                            <input type="datetime-local" name="date_heure" class="form-control" 
                                   value="<?= date('Y-m-d\TH:i', strtotime($eventData['date_heure'])) ?>" required
                                   style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Prix (€)</label>
                            <div class="input-group">
                                <input type="number" name="prix" class="form-control" step="0.01" min="0" 
                                       value="<?= $eventData['prix'] ?>"
                                       style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                                <span class="input-group-text" style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">€</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Lieu *</label>
                            <select name="id_venue" class="form-select" required
                                    style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                                <option value="">Sélectionner un lieu</option>
                                <?php foreach ($venues as $v): ?>
                                <option value="<?= $v['id_venue'] ?>" <?= ($eventData['id_venue'] == $v['id_venue']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['nom']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color: var(--text-primary);">Artiste</label>
                            <select name="id_artiste" class="form-select"
                                    style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                                <option value="">Sélectionner un artiste</option>
                                <?php foreach ($artistes as $art): ?>
                                <option value="<?= $art['id_artiste'] ?>" <?= ($eventData['id_artiste'] == $art['id_artiste']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($art['nom']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Changer l'image</label>
                    <input type="file" name="image" class="form-control" accept="image/*"
                           style="background: #2a2a3e; border-color: #444; color: var(--text-primary);">
                    <small style="color: var(--text-secondary);">Laissez vide pour conserver l'image actuelle</small>
                </div>

                <?php if (!empty($eventData['image'])): ?>
                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-primary);">Image actuelle :</label>
                    <div>
                        <img src="../uploads/<?= htmlspecialchars($eventData['image']) ?>" 
                             alt="Image actuelle" class="img-thumbnail" style="max-width: 200px; border-color: #444;">
                    </div>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <a href="evenements.php" class="btn btn-secondary btn-action">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary btn-action">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
