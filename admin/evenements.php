<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';
require_once '../classes/Venue.php';
require_once '../classes/Artiste.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);
$venue = new Venue($db);
$artiste = new Artiste($db);

$message = '';
$error = '';

// Gestion des messages de retour
if (isset($_GET['success']) && $_GET['success'] === 'suppression') {
    $message = "Événement supprimé avec succès.";
}
if (isset($_GET['error']) && $_GET['error'] === 'not_found') {
    $error = "Événement introuvable.";
}

$evenements = $evenement->lire();

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
        <h1><i class="fas fa-calendar"></i> Dashboard – Événements</h1>
        <a href="evenement_ajouter.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un événement
        </a>
    </div>

    <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
        <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
            <h5 class="mb-0"><i class="fas fa-list"></i> Liste des événements</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="background: var(--surface-dark);">
                    <thead style="background: #23233a;">
                        <tr>
                            <th style="border-color: #444; color: var(--accent-color);">ID</th>
                            <th style="border-color: #444; color: var(--accent-color);">Titre</th>
                            <th style="border-color: #444; color: var(--accent-color);">Date</th>
                            <th style="border-color: #444; color: var(--accent-color);">Lieu</th>
                            <th style="border-color: #444; color: var(--accent-color);">Artiste</th>
                            <th style="border-color: #444; color: var(--accent-color);">Prix (€)</th>
                            <th class="text-end" style="border-color: #444; color: var(--accent-color);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($evenements)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-3" style="color: var(--text-secondary); border-color: #444;">Aucun événement enregistré</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($evenements as $evt): ?>
                        <tr style="border-color: #444;">
                            <td style="color: var(--text-primary); border-color: #444;"><?= $evt['id_evenement'] ?></td>
                            <td style="color: var(--text-primary); border-color: #444;"><?= htmlspecialchars($evt['titre']) ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($evt['venue_nom'] ?? 'Non défini') ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($evt['artiste_nom'] ?? 'Non défini') ?></td>
                            <td style="color: var(--accent-color); font-weight: 600; border-color: #444;"><?= number_format($evt['prix'], 2) ?></td>
                            <td class="text-end" style="border-color: #444;">
                                <a href="evenement_modifier.php?id=<?= $evt['id_evenement'] ?>" 
                                   class="btn btn-warning btn-sm btn-action me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="evenement_supprimer.php?id=<?= $evt['id_evenement'] ?>" 
                                   class="btn btn-danger btn-sm btn-action">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="tickets_evenement.php?evenement_id=<?= $evt['id_evenement'] ?>" 
                                   class="btn btn-success btn-sm btn-action">
                                    <i class="fas fa-ticket-alt"></i>
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
