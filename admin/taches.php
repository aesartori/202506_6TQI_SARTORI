<?php
require_once '../config/database.php';
require_once '../classes/Tache.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$tache = new Tache($db);
$evenement = new Evenement($db);

$message = '';
$error = '';

// Traitement suppression
if (isset($_GET['supprimer'])) {
    if ($tache->supprimer($_GET['supprimer'])) {
        $message = "Tâche supprimée avec succès.";
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

// Traitement ajout rapide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $data = [
        'titre' => $_POST['titre'],
        'description' => $_POST['description'],
        'date_echeance' => $_POST['date_echeance'],
        'statut' => $_POST['statut'],
        'priorite' => $_POST['priorite'],
        'evenement_id' => $_POST['evenement_id'] ?: null
    ];
    
    if ($tache->creer($data)) {
        $message = "Tâche créée avec succès.";
    } else {
        $error = "Erreur lors de la création.";
    }
}

$taches = $tache->lire();
$evenements = $evenement->lire();

include 'includes/header.php';
?>

<?php if ($message): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= $message ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?= $error ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-tasks"></i> Gestion des tâches</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAjouter">
        <i class="fas fa-plus"></i> Nouvelle tâche
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Événement</th>
                        <th>Échéance</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($taches as $t): ?>
                    <tr>
                        <td><?= $t['id'] ?></td>
                        <td><?= htmlspecialchars($t['titre']) ?></td>
                        <td><?= $t['evenement_nom'] ? htmlspecialchars($t['evenement_nom']) : '-' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($t['date_echeance'])) ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $t['priorite'] === 'haute' ? 'danger' : 
                                ($t['priorite'] === 'moyenne' ? 'warning' : 'info') 
                            ?> status-badge">
                                <?= ucfirst($t['priorite']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?= 
                                $t['statut'] === 'termine' ? 'success' : 
                                ($t['statut'] === 'en_cours' ? 'warning' : 'secondary') 
                            ?> status-badge">
                                <?= ucfirst(str_replace('_', ' ', $t['statut'])) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="tache_modifier.php?id=<?= $t['id'] ?>" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?supprimer=<?= $t['id'] ?>" 
                                   class="btn btn-sm btn-danger" title="Supprimer"
                                   onclick="return confirmerSuppression(this, 'tâche')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajouter Tâche -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Nouvelle tâche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="ajouter">
                    
                    <div class="mb-3">
                        <label class="form-label">Titre *</label>
                        <input type="text" class="form-control" name="titre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date d'échéance *</label>
                                <input type="datetime-local" class="form-control" name="date_echeance" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Événement lié</label>
                                <select class="form-select" name="evenement_id">
                                    <option value="">Aucun événement</option>
                                    <?php foreach($evenements as $evt): ?>
                                    <option value="<?= $evt['id'] ?>"><?= htmlspecialchars($evt['nom']) ?></option>
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
                                    <option value="basse">Basse</option>
                                    <option value="moyenne" selected>Moyenne</option>
                                    <option value="haute">Haute</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Statut *</label>
                                <select class="form-select" name="statut" required>
                                    <option value="a_faire" selected>À faire</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="termine">Terminé</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer la tâche
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>