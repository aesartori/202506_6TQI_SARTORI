<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);

$id = $_GET['id'] ?? 0;
$eventData = $evenement->lireUn($id);

if (!$eventData) {
    header("Location: evenements.php?error=not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'],
        'description' => $_POST['description'],
        'date_debut' => $_POST['date_debut'],
        'date_fin' => $_POST['date_fin'] ?: null,
        'lieu' => $_POST['lieu'],
        'capacite' => $_POST['capacite'] ?: null,
        'prix' => $_POST['prix'] ?: 0,
        'statut' => $_POST['statut']
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-edit"></i> Modifier l'événement</h1>
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
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nom de l'événement *</label>
                        <input type="text" class="form-control" name="nom" 
                               value="<?= htmlspecialchars($eventData['nom']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Lieu *</label>
                        <input type="text" class="form-control" name="lieu" 
                               value="<?= htmlspecialchars($eventData['lieu']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($eventData['description']) ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Date de début *</label>
                        <input type="datetime-local" class="form-control" name="date_debut" 
                               value="<?= date('Y-m-d\TH:i', strtotime($eventData['date_debut'])) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Date de fin</label>
                        <input type="datetime-local" class="form-control" name="date_fin" 
                               value="<?= $eventData['date_fin'] ? date('Y-m-d\TH:i', strtotime($eventData['date_fin'])) : '' ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Capacité</label>
                        <input type="number" class="form-control" name="capacite" 
                               value="<?= $eventData['capacite'] ?>" min="1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Prix (€)</label>
                        <input type="number" step="0.01" class="form-control" name="prix" 
                               value="<?= $eventData['prix'] ?>" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Statut *</label>
                        <select class="form-select" name="statut" required>
                            <option value="planifie" <?= $eventData['statut'] === 'planifie' ? 'selected' : '' ?>>Planifié</option>
                            <option value="en_cours" <?= $eventData['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="termine" <?= $eventData['statut'] === 'termine' ? 'selected' : '' ?>>Terminé</option>
                            <option value="annule" <?= $eventData['statut'] === 'annule' ? 'selected' : '' ?>>Annulé</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="evenements.php" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Modifier l'événement
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>