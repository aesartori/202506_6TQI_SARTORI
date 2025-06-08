<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';
require_once '../classes/Venue.php';
require_once '../classes/Artiste.php';
require_once '../classes/Ticket.php';

$database = new Database();
$db = $database->getConnection();

$evenement = new Evenement($db);
$venue = new Venue($db);
$artiste = new Artiste($db);
$ticket = new Ticket($db);

$evenements = $evenement->lire();
$venues = $venue->lire();
$artistes = $artiste->lire();
$prochainsEvenements = $evenement->getProchains(5);

include 'includes/header.php';
?>

<main class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= count($evenements) ?></h4>
                            <p class="mb-0">Événements</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= count($venues) ?></h4>
                            <p class="mb-0">Lieux</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white mb-4 shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?= count($artistes) ?></h4>
                            <p class="mb-0">Artistes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau dark mode amélioré -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
                <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Prochains événements</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0" style="background: var(--surface-dark);">
                            <thead style="background: #23233a;">
                                <tr>
                                    <th style="border-color: #444; color: var(--accent-color);">Titre</th>
                                    <th style="border-color: #444; color: var(--accent-color);">Date</th>
                                    <th style="border-color: #444; color: var(--accent-color);">Lieu</th>
                                    <th style="border-color: #444; color: var(--accent-color);">Artiste</th>
                                    <th style="border-color: #444; color: var(--accent-color);">Prix (€)</th>
                                    <th style="border-color: #444; color: var(--accent-color);">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($prochainsEvenements)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-3" style="color: var(--text-secondary); border-color: #444;">
                                            Aucun événement à venir
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($prochainsEvenements as $evt): ?>
                                    <tr style="border-color: #444;">
                                        <td style="color: var(--text-primary); border-color: #444;"><?= htmlspecialchars($evt['titre']) ?></td>
                                        <td style="color: var(--text-secondary); border-color: #444;"><?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?></td>
                                        <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($evt['venue_nom'] ?? 'Non défini') ?></td>
                                        <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($evt['artiste_nom'] ?? 'Non défini') ?></td>
                                        <td style="color: var(--accent-color); font-weight: 600; border-color: #444;"><?= number_format($evt['prix'], 2) ?></td>
                                        <td style="border-color: #444;">
                                            <a href="evenement_modifier.php?id=<?= $evt['id_evenement'] ?>" class="btn btn-sm btn-warning btn-action me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="tickets_evenement.php?evenement_id=<?= $evt['id_evenement'] ?>" class="btn btn-sm btn-success btn-action">
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
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
