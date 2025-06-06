<?php
require_once '../config/database.php';
require_once '../classes/Tache.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$tache = new Tache($db);
$evenement = new Evenement($db);

$id = $_GET['id'] ?? 0;
$tacheData = $tache->lireUn($id);

if (!$tacheData) {
    header("Location: taches.php?error=not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'date_echeance' => $_POST['date_echeance'],
        'statut' => $_POST['statut'],
        'priorite' => $_POST['priorite'],
        'evenement_id' => $_POST['evenement_id'] ?: null
    ];
    
    if ($tache->modifier($id, $data)) {
        header("Location: taches.php?success=modification");
        exit();
    } else {
        $error = "Erreur lors de la modification";
    }
}

$evenements = $evenement->lire();

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-task"></i> Modifier la tâche</h1>
    <a href="taches.php" class="btn btn-secondary">
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
            <div class="mb-3">
                <label class="form-label">Titre *</label>
                <input type="text" class="form-control" name="titre" 
                       value="<?= htmlspecialchars($tacheData['titre']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($tacheData['description']) ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Date d'échéance *</label>
                        <input type="datetime-local" class="form-control" name="date_echeance" 
                               value="<?= date('Y-m-d\TH:i', strtotime($tacheData['date_echeance'])) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Événement lié</label>
                        <select class="form-select" name="evenement_id">
                            <option value="">Aucun événement</option>
                            <?php foreach($evenements as $evt): ?>
                            <option value="<?= $evt['id'] ?>" <?= $tacheData['evenement_id'] == $evt['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($evt['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Priorité *</label>
                        <select class="form-select" name="priorite" required>
                            <option value="basse" <?= $tacheData['priorite'] === 'basse' ? 'selected' : '' ?>>Basse</option>
                            <option value="moyenne" <?= $tacheData['priorite'] === 'moyenne' ? 'selected' : '' ?>>Moyenne</option>
                            <option value="haute" <?= $tacheData['priorite'] === 'haute' ? 'selected' : '' ?>>Haute</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Statut *</label>
                        <select class="form-select" name="statut" required>
                            <option value="a_faire" <?= $tacheData['statut'] === 'a_faire' ? 'selected' : '' ?>>À faire</option>
                            <option value="en_cours" <?= $tacheData['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="termine" <?= $tacheData['statut'] === 'termine' ? 'selected' : '' ?>>Terminé</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="taches.php" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Modifier la tâche
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>