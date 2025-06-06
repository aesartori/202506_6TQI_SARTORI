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
    <h1><i class="fas fa-users"></i> Gestion des artistes</h1>
    <a href="artiste_ajouter.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel artiste
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom complet</th>
                        <th>Spécialité</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artistes as $art): ?>
                    <tr>
                        <td><?= $art['id'] ?></td>
                        <td><?= htmlspecialchars($art['prenom'] . ' ' . $art['nom']) ?></td>
                        <td><?= htmlspecialchars($art['specialite']) ?></td>
                        <td><?= htmlspecialchars($art['email']) ?></td>
                        <td><?= htmlspecialchars($art['telephone']) ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="artiste_modifier.php?id=<?= $art['id'] ?>" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?supprimer=<?= $art['id'] ?>" 
                                   class="btn btn-sm btn-danger" title="Supprimer"
                                   onclick="return confirmerSuppression(this, 'artiste')">
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