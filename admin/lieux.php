<?php
require_once '../config/database.php';
require_once '../classes/Venue.php';

$database = new Database();
$db = $database->getConnection();
$venue = new Venue($db);

$message = '';
$error = '';

// Vérifie si une suppression a été effectuée via une redirection (optionnel)
if (isset($_GET['success']) && $_GET['success'] === 'suppression') {
    $message = "Lieu supprimé avec succès.";
}
if (isset($_GET['error']) && $_GET['error'] === 'not_found') {
    $error = "Lieu introuvable.";
}

$venues = $venue->lire();

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

    <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
        <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
            <h5 class="mb-0"><i class="fas fa-list"></i> Liste des lieux</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="background: var(--surface-dark);">
                    <thead style="background: #23233a;">
                        <tr>
                            <th style="border-color: #444; color: var(--accent-color);">ID</th>
                            <th style="border-color: #444; color: var(--accent-color);">Nom</th>
                            <th style="border-color: #444; color: var(--accent-color);">Type</th>
                            <th style="border-color: #444; color: var(--accent-color);">Adresse</th>
                            <th style="border-color: #444; color: var(--accent-color);">URL</th>
                            <th style="border-color: #444; color: var(--accent-color);">Photo</th>
                            <th class="text-end" style="border-color: #444; color: var(--accent-color);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($venues)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-3" style="color: var(--text-secondary); border-color: #444;">Aucun lieu enregistré</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($venues as $v): ?>
                        <tr style="border-color: #444;">
                            <td style="color: var(--text-primary); border-color: #444;"><?= $v['id_venue'] ?></td>
                            <td style="color: var(--text-primary); border-color: #444;"><?= htmlspecialchars($v['nom']) ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($v['type'] ?? '-') ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($v['adresse'] ?? '-') ?></td>
                            <td style="border-color: #444;">
                                <?php if (!empty($v['url'])): ?>
                                    <a href="<?= htmlspecialchars($v['url']) ?>" target="_blank" style="color: var(--accent-color);">
                                        <?= htmlspecialchars($v['url']) ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: var(--text-secondary);">-</span>
                                <?php endif; ?>
                            </td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($v['photo'] ?? '-') ?></td>
                            <td class="text-end" style="border-color: #444;">
                                <a href="lieu_modifier.php?id=<?= $v['id_venue'] ?>" 
                                   class="btn btn-warning btn-sm btn-action me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="lieu_supprimer.php?id=<?= $v['id_venue'] ?>" 
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
