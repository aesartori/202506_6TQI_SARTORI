<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);

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
    
    if ($evenement->creer($data)) {
        header("Location: evenements.php?success=creation");
        exit();
    } else {
        $error = "Erreur lors de la création";
    }
}

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-plus"></i> Nouvel événement</h1>
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
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Lieu *</label>
                        <input type="text" class="form-control" name="lieu" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4"></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Date de début *</label>
                        <input type="datetime-local" class="form-control" name="date_debut" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Date de fin</label>
                        <input type="datetime-local" class="form-control" name="date_fin">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Capacité</label>
                        <input type="number" class="form-control" name="capacite" min="1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Prix (€)</label>
                        <input type="number" step="0.01" class="form-control" name="prix" min="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Statut *</label>
                        <select class="form-select" name="statut" required>
                            <option value="planifie">Planifié</option>
                            <option value="en_cours">En cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="evenements.php" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Créer l'événement
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>