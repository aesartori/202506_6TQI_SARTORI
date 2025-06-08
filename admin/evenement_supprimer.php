<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';
require_once '../classes/Ticket.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);
$ticket = new Ticket($db);

$id = $_GET['id'] ?? 0;
$eventData = $evenement->lireUn($id);

if (!$eventData) {
    header("Location: evenements.php?error=not_found");
    exit();
}

// Récupérer les tickets liés à l'événement
$ticketsLies = $ticket->lireParEvenement($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
        try {
            // La suppression en cascade des tickets est gérée par la contrainte ON DELETE CASCADE
            // (voir ta base de données)
            if ($evenement->supprimer($id)) {
                header("Location: evenements.php?success=suppression");
                exit();
            } else {
                $error = "Erreur lors de la suppression";
            }
        } catch (PDOException $e) {
            $error = "Erreur : Impossible de supprimer l'événement.";
        }
    } else {
        header("Location: evenements.php");
        exit();
    }
}

include 'includes/header.php';
?>

<main class="container py-4">
    <div class="mb-4">
        <h1 class="text-danger">
            <i class="fas fa-trash-alt"></i> Confirmation de suppression
        </h1>
    </div>

    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="fas fa-exclamation-triangle"></i> Attention !
            </h5>
        </div>
        <div class="card-body">
            <p class="lead">Vous êtes sur le point de supprimer définitivement :</p>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5><?= htmlspecialchars($eventData['titre']) ?></h5>
                    <ul class="list-unstyled">
                        <li><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($eventData['date_heure'])) ?></li>
                        <li><strong>Lieu :</strong> <?= htmlspecialchars($eventData['venue_nom'] ?? 'Non défini') ?></li>
                        <li><strong>Artiste :</strong> <?= htmlspecialchars($eventData['artiste_nom'] ?? 'Non défini') ?></li>
                        <li><strong>Prix :</strong> <?= number_format($eventData['prix'], 2) ?> €</li>
                    </ul>
                </div>
                <?php if (!empty($eventData['image'])): ?>
                <div class="col-md-6">
                    <img src="../uploads/<?= htmlspecialchars($eventData['image']) ?>" 
                         alt="Image de l'événement" class="img-fluid rounded" style="max-width: 200px;">
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($ticketsLies)): ?>
            <div class="alert alert-warning">
                <h5><i class="fas fa-ticket-alt"></i> Tickets associés</h5>
                <p>Cet événement est lié à <?= count($ticketsLies) ?> ticket(s) :</p>
                <ul>
                    <?php foreach ($ticketsLies as $t): ?>
                    <li>
                        <?= htmlspecialchars($t['code_unique']) ?> – <?= htmlspecialchars($t['nom_complet']) ?>
                        (<?= date('d/m/Y H:i', strtotime($t['date_reservation'])) ?>)
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supprimer_tickets" 
                           id="supprimerTickets" checked disabled>
                    <label class="form-check-label" for="supprimerTickets">
                        Les tickets associés seront automatiquement supprimés
                    </label>
                </div>
            </div>
            <?php endif; ?>

            <div class="alert alert-dark">
                <i class="fas fa-info-circle"></i> Cette action est irréversible et supprimera 
                toutes les données associées de manière permanente.
            </div>

            <form method="post">
                <div class="d-flex justify-content-between mt-4">
                    <a href="evenements.php" class="btn btn-lg btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" name="confirm" value="oui" 
                            class="btn btn-lg btn-danger">
                        <i class="fas fa-trash-alt"></i> Confirmer la suppression
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
