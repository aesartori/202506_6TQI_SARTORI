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

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-ticket-alt"></i> Tickets achetés – <?= htmlspecialchars($event['titre']) ?></h2>
        <a href="tickets.php" class="btn btn-secondary btn-action">
            <i class="fas fa-arrow-left"></i> Retour aux événements
        </a>
    </div>

    <div class="alert" style="background: #2a2a3e; color: var(--text-primary); border: 1px solid #444;">
        <strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($event['date_heure'])) ?> | 
        <strong>Lieu :</strong> <?= $venueEvent ? htmlspecialchars($venueEvent['nom']) : 'Non défini' ?>
    </div>

    <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
        <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
            <h5 class="mb-0"><i class="fas fa-list"></i> Liste des tickets</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="background: var(--surface-dark);">
                    <thead style="background: #23233a;">
                        <tr>
                            <th style="border-color: #444; color: var(--accent-color);">#</th>
                            <th style="border-color: #444; color: var(--accent-color);">Code</th>
                            <th style="border-color: #444; color: var(--accent-color);">Nom de l'acheteur</th>
                            <th style="border-color: #444; color: var(--accent-color);">Quantité</th>
                            <th style="border-color: #444; color: var(--accent-color);">Date d'achat</th>
                            <th style="border-color: #444; color: var(--accent-color);">Statut</th>
                            <th style="border-color: #444; color: var(--accent-color);">Utilisé ?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-3" style="color: var(--text-secondary); border-color: #444;">Aucun ticket pour cet événement</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($tickets as $i => $t): ?>
                        <tr style="border-color: #444;">
                            <td style="color: var(--text-primary); border-color: #444;"><?= $i + 1 ?></td>
                            <td style="color: var(--text-primary); border-color: #444;"><code><?= htmlspecialchars($t['code_unique']) ?></code></td>
                            <td style="color: var(--text-primary); border-color: #444;"><?= htmlspecialchars($t['nom_complet']) ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= (int)$t['quantite'] ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= date('Y-m-d H:i', strtotime($t['date_reservation'])) ?></td>
                            <td style="border-color: #444;">
                                <span class="badge bg-<?= 
                                    $t['statut'] === 'Payé' ? 'success' : 
                                    ($t['statut'] === 'En attente' ? 'warning' : 'danger') ?>">
                                    <?= htmlspecialchars($t['statut']) ?>
                                </span>
                            </td>
                            <td style="border-color: #444;">
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
</main>

<?php include 'includes/footer.php'; ?>
