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

<main class="container py-4">
    <?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert alert2-danger alert-dismissible fade show">
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

    <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
        <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
            <h5 class="mb-0"><i class="fas fa-list"></i> Liste des artistes</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="background: var(--surface-dark);">
                    <thead style="background: #23233a;">
                        <tr>
                            <th style="border-color: #444; color: var(--accent-color);">ID</th>
                            <th style="border-color: #444; color: var(--accent-color);">Nom</th>
                            <th style="border-color: #444; color: var(--accent-color);">URL</th>
                            <th style="border-color: #444; color: var(--accent-color);">Photo</th>
                            <th class="text-end" style="border-color: #444; color: var(--accent-color);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($artistes)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3" style="color: var(--text-secondary); border-color: #444;">Aucun artiste enregistré</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($artistes as $art): ?>
                        <tr style="border-color: #444;">
                            <td style="color: var(--text-primary); border-color: #444;"><?= $art['id_artiste'] ?></td>
                            <td style="color: var(--text-primary); border-color: #444;"><?= htmlspecialchars($art['nom']) ?></td>
                            <td style="border-color: #444;">
                                <?php if (!empty($art['url'])): ?>
                                    <a href="<?= htmlspecialchars($art['url']) ?>" target="_blank" style="color: var(--accent-color);">
                                        <?= htmlspecialchars($art['url']) ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: var(--text-secondary);">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($art['photo'] ?? '-') ?></td>
                            <td class="text-end" style="border-color: #444;">
                                <a href="artiste_modifier.php?id=<?= $art['id_artiste'] ?>" 
                                class="btn btn-warning btn-sm btn-action me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="artiste_supprimer.php?id=<?= $art['id_artiste'] ?>" 
                                class="btn btn-danger btn-sm btn-action">
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
</main>

<?php include 'includes/footer.php'; ?>

<script>
function confirmerSuppression(link, type) {
    if(confirm("Êtes-vous sûr de vouloir supprimer cet " + type + " ?")) {
        return true;
    }
    return false;
}
</script>
