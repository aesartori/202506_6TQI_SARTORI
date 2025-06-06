<?php
require_once '../config/database.php';
require_once '../classes/Artiste.php';

$database = new Database();
$db = $database->getConnection();
$artiste = new Artiste($db);

$message = '';
$error = '';

// Traitement suppression
if (isset($_GET['supprimer'])) {
    if ($artiste->supprimer($_GET['supprimer'])) {
        $message = "Artiste supprimé avec succès.";
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

$artistes = $artiste->lire();

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
    <h1><i class="fas fa-users"></i> Liste des artistes</h1>
    <a href="artiste_ajouter.php" class="btn btn-success">
        <i class="fas fa-plus"></i> Ajouter un artiste
    </a>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Liste des artistes</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>URL</th>
                        <th>Photo</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($artistes)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucun artiste enregistré</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($artistes as $art): ?>
                    <tr>
                        <td><?= $art['id_artiste'] ?></td>
                        <td><?= htmlspecialchars($art['nom']) ?></td>
                        <td>
                            <?php if (!empty($art['url'])): ?>
                                <a href="<?= htmlspecialchars($art['url']) ?>" target="_blank" class="text-primary">
                                    <?= htmlspecialchars($art['url']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($art['photo'] ?? '-') ?></td>
                        <td class="text-end">
                            <a href="artiste_modifier.php?id=<?= $art['id_artiste'] ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?supprimer=<?= $art['id_artiste'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirmerSuppression(this, 'artiste')">
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
