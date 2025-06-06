<?php
require_once '../config/database.php';
require_once '../classes/Ticket.php';
require_once '../classes/Evenement.php';
require_once '../classes/Venue.php';

$database = new Database();
$db = $database->getConnection();
$ticket = new Ticket($db);
$evenement = new Evenement($db);
$venue = new Venue($db);

$evenement_id = $_GET['evenement_id'] ?? 0;
$event = $evenement->lireUn($evenement_id);

if (!$event) {
    header("Location: tickets.php");
    exit();
}

$venueEvent = $venue->lireUn($event['id_venue']);
$tickets = $ticket->lireParEvenement($evenement_id);

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-ticket-alt"></i> Tickets achetés – <?= htmlspecialchars($event['titre']) ?></h2>
        <a href="tickets.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux événements
        </a>
    </div>

    <div class="alert alert-info">
        <strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($event['date_heure'])) ?> | 
        <strong>Lieu :</strong> <?= $venueEvent ? htmlspecialchars($venueEvent['nom']) : 'Non défini' ?>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Liste des tickets</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Nom de l'acheteur</th>
                            <th>Quantité</th>
                            <th>Date d'achat</th>
                            <th>Statut</th>
                            <th>Utilisé ?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucun ticket pour cet événement</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($tickets as $i => $t): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><code><?= htmlspecialchars($t['code_unique']) ?></code></td>
                            <td><?= htmlspecialchars($t['nom_complet']) ?></td>
                            <td><?= (int)$t['quantite'] ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($t['date_reservation'])) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $t['statut'] === 'Payé' ? 'success' : 
                                    ($t['statut'] === 'En attente' ? 'warning' : 'danger') ?>">
                                    <?= htmlspecialchars($t['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($t['utilise']): ?>
                                    <span class="badge bg-success">Oui</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non</span>
                                <?php endif; ?>
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

<?php include 'includes/footer.php'; ?>
