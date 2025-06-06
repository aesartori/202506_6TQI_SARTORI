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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($evt['titre']) ?></h5>
                <p class="card-text">
                    <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?><br>
                    <i class="fas fa-ticket-alt"></i> <?= $nbTickets ?> ticket(s)
                </p>
                <a href="tickets_evenement.php?evenement_id=<?= $evt['id_evenement'] ?>" 
                   class="btn btn-primary">
                    Voir les tickets
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Tableau global des tickets -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="fas fa-list"></i> Tous les tickets</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Événement</th>
                        <th>Acheteur</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Utilisé</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tickets)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun ticket enregistré</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($t['code_unique']) ?></code></td>
                        <td><?= htmlspecialchars($t['evenement_titre']) ?></td>
                        <td><?= htmlspecialchars($t['nom_complet']) ?></td>
                        <td><?= (int)$t['quantite'] ?></td>
                        <td><?= number_format($t['prix_total'], 2) ?>€</td>
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
                        <td class="text-end">
                            <a href="?supprimer=<?= $t['id_ticket'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirmerSuppression(this, 'ticket')">
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
