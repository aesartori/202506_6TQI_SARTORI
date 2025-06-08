<?php
require_once '../config/database.php';
require_once '../classes/Ticket.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$ticket = new Ticket($db);
$evenement = new Evenement($db);

$message = '';
$error = '';

// Suppression
if (isset($_GET['supprimer'])) {
    if ($ticket->supprimer($_GET['supprimer'])) {
        $message = "Ticket supprimé avec succès.";
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

$tickets = $ticket->lire();
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
        <h1><i class="fas fa-ticket-alt"></i> Gestion des tickets</h1>
    </div>

    <!-- Liste des événements avec nombre de tickets -->
    <div class="row mb-4">
        <?php foreach ($evenements as $evt): 
            $ticketsEvent = $ticket->lireParEvenement($evt['id_evenement']);
            $nbTickets = count($ticketsEvent);
        ?>
        <div class="col-md-4 mb-3">
            <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--text-primary);"><?= htmlspecialchars($evt['titre']) ?></h5>
                    <p class="card-text" style="color: var(--text-secondary);">
                        <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?><br>
                        <i class="fas fa-ticket-alt"></i> <?= $nbTickets ?> ticket(s)
                    </p>
                    <a href="tickets_evenement.php?evenement_id=<?= $evt['id_evenement'] ?>" 
                       class="btn btn-primary btn-action">
                        Voir les tickets
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Tableau global des tickets -->
    <div class="card shadow" style="background: var(--surface-dark); border: 1px solid #333;">
        <div class="card-header" style="background: linear-gradient(45deg, var(--accent-color), #9D67E7); color: white; border-bottom: 1px solid #333;">
            <h5 class="mb-0"><i class="fas fa-list"></i> Tous les tickets</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="background: var(--surface-dark);">
                    <thead style="background: #23233a;">
                        <tr>
                            <th style="border-color: #444; color: var(--accent-color);">Code</th>
                            <th style="border-color: #444; color: var(--accent-color);">Événement</th>
                            <th style="border-color: #444; color: var(--accent-color);">Acheteur</th>
                            <th style="border-color: #444; color: var(--accent-color);">Quantité</th>
                            <th style="border-color: #444; color: var(--accent-color);">Total</th>
                            <th style="border-color: #444; color: var(--accent-color);">Statut</th>
                            <th style="border-color: #444; color: var(--accent-color);">Utilisé</th>
                            <th class="text-end" style="border-color: #444; color: var(--accent-color);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-3" style="color: var(--text-secondary); border-color: #444;">Aucun ticket enregistré</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($tickets as $t): ?>
                        <tr style="border-color: #444;">
                            <td style="color: var(--text-primary); border-color: #444;"><code><?= htmlspecialchars($t['code_unique']) ?></code></td>
                            <td style="color: var(--text-primary); border-color: #444;"><?= htmlspecialchars($t['evenement_titre']) ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= htmlspecialchars($t['nom_complet']) ?></td>
                            <td style="color: var(--text-secondary); border-color: #444;"><?= (int)$t['quantite'] ?></td>
                            <td style="color: var(--accent-color); font-weight: 600; border-color: #444;"><?= number_format($t['prix_total'], 2) ?>€</td>
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
                            <td class="text-end" style="border-color: #444;">
                                <a href="ticket_supprimer.php?id=<?= $t['id_ticket'] ?>" 
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
    if(confirm("Êtes-vous sûr de vouloir supprimer ce " + type + " ?")) {
        return true;
    }
    return false;
}
</script>
