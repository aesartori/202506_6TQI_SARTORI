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
    <h1><i class="fas fa-calendar"></i> Dashboard – Événements</h1>
    <a href="evenement_ajouter.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un événement
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Artiste</th>
                        <th>Prix (€)</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($evenements)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucun événement enregistré</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($evenements as $evt): ?>
                    <tr>
                        <td><?= $evt['id_evenement'] ?></td>
                        <td><?= htmlspecialchars($evt['titre']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?></td>
                        <td><?= htmlspecialchars($evt['venue_nom'] ?? 'Non défini') ?></td>
                        <td><?= htmlspecialchars($evt['artiste_nom'] ?? 'Non défini') ?></td>
                        <td><?= number_format($evt['prix'], 2) ?></td>
                        <td class="text-end">
                            <a href="evenement_modifier.php?id=<?= $evt['id_evenement'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?supprimer=<?= $evt['id_evenement'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirmerSuppression(this, 'événement')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <a href="tickets_evenement.php?evenement_id=<?= $evt['id_evenement'] ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-ticket-alt"></i> Tickets
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
