<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);

$message = '';
$error = '';

// Traitement suppression
if (isset($_GET['supprimer'])) {
    if ($evenement->supprimer($_GET['supprimer'])) {
        $message = "Événement supprimé avec succès.";
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

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
    <h1><i class="fas fa-calendar"></i> Gestion des événements</h1>
    <a href="evenement_ajouter.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel événement
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Lieu</th>
                        <th>Capacité</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($evenements as $evt): ?>
                    <tr>
                        <td><?= $evt['id'] ?></td>
                        <td><?= htmlspecialchars($evt['nom']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($evt['date_debut'])) ?></td>
                        <td><?= $evt['date_fin'] ? date('d/m/Y H:i', strtotime($evt['date_fin'])) : '-' ?></td>
                        <td><?= htmlspecialchars($evt['lieu']) ?></td>
                        <td><?= $evt['capacite'] ?? '-' ?></td>
                        <td><?= $evt['prix'] ? number_format($evt['prix'], 2) . '€' : 'Gratuit' ?></td>
                        <td>
                            <span class="badge bg-<?= 
                                $evt['statut'] === 'planifie' ? 'primary' : 
                                ($evt['statut'] === 'en_cours' ? 'warning' : 
                                ($evt['statut'] === 'termine' ? 'success' : 'danger')) 
                            ?> status-badge">
                                <?= ucfirst(str_replace('_', ' ', $evt['statut'])) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="evenement_modifier.php?id=<?= $evt['id'] ?>" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="../front/evenement.php?id=<?= $evt['id'] ?>" 
                                   class="btn btn-sm btn-info" title="Voir" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="?supprimer=<?= $evt['id'] ?>" 
                                   class="btn btn-sm btn-danger" title="Supprimer"
                                   onclick="return confirmerSuppression(this, 'événement')">
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

<?php include 'includes/footer.php'; ?>