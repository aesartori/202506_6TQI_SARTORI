<?php
require_once '../config/database.php';
require_once '../classes/Venue.php';

$database = new Database();
$db = $database->getConnection();
$venue = new Venue($db);

$message = '';
$error = '';

// Traitement suppression
if (isset($_GET['supprimer'])) {
    if ($venue->supprimer($_GET['supprimer'])) {
        $message = "Lieu supprimé avec succès.";
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

$venues = $venue->lire();

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
    <h1><i class="fas fa-map-marker-alt"></i> Liste des lieux</h1>
    <a href="lieu_ajouter.php" class="btn btn-success">
        <i class="fas fa-plus"></i> Ajouter un lieu
    </a>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Liste des lieux</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Adresse</th>
                        <th>URL</th>
                        <th>Photo</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($venues)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucun lieu enregistré</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($venues as $v): ?>
                    <tr>
                        <td><?= $v['id_venue'] ?></td>
                        <td><?= htmlspecialchars($v['nom']) ?></td>
                        <td><?= htmlspecialchars($v['type'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($v['adresse'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($v['url'])): ?>
                                <a href="<?= htmlspecialchars($v['url']) ?>" target="_blank" class="text-primary">
                                    <?= htmlspecialchars($v['url']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($v['photo'] ?? '-') ?></td>
                        <td class="text-end">
                            <a href="lieu_modifier.php?id=<?= $v['id_venue'] ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?supprimer=<?= $v['id_venue'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirmerSuppression(this, 'lieu')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
