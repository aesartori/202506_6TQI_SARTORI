<?php
require_once '../config/database.php';
require_once '../classes/Ticket.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$ticket = new Ticket($db);
$evenement = new Evenement($db);

$id = $_GET['id'] ?? 0;
$ticketData = $ticket->lireUn($id);

if (!$ticketData) {
    header("Location: tickets.php?error=not_found");
    exit();
}

$eventData = $evenement->lireUn($ticketData['id_evenement']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
        if ($ticket->supprimer($id)) {
            header("Location: tickets.php?success=suppression");
            exit();
        } else {
            $error = "Erreur lors de la suppression";
        }
    } else {
        header("Location: tickets.php");
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
                    <h5>Ticket #<?= $ticketData['id_ticket'] ?></h5>
                    <ul class="list-unstyled">
                        <li><strong>Code :</strong> <code><?= htmlspecialchars($ticketData['code_unique']) ?></code></li>
                        <li><strong>Acheteur :</strong> <?= htmlspecialchars($ticketData['nom_complet']) ?></li>
                        <li><strong>Événement :</strong> <?= htmlspecialchars($ticketData['evenement_titre']) ?></li>
                        <li><strong>Date réservation :</strong> <?= date('d/m/Y H:i', strtotime($ticketData['date_reservation'])) ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="alert" style="background: #2a2a3e; color: var(--text-primary);">
                        <h5>Détails financiers</h5>
                        <ul class="list-unstyled">
                            <li>Quantité : <?= $ticketData['quantite'] ?></li>
                            <li>Prix unitaire : <?= number_format($ticketData['prix_personne'], 2) ?>€</li>
                            <li>Total : <span class="text-success"><?= number_format($ticketData['prix_total'], 2) ?>€</span></li>
                            <li>Statut : <span class="badge bg-<?= $ticketData['statut'] === 'Payé' ? 'success' : 'warning' ?>">
                                <?= $ticketData['statut'] ?>
                            </span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="alert alert-dark">
                <i class="fas fa-info-circle"></i> Cette action est irréversible et supprimera 
                toutes les données associées de manière permanente.
            </div>

            <form method="post">
                <div class="d-flex justify-content-between mt-4">
                    <a href="tickets.php" class="btn btn-lg btn-secondary btn-action">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" name="confirm" value="oui" 
                            class="btn btn-lg btn-danger btn-action">
                        <i class="fas fa-trash-alt"></i> Confirmer la suppression
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
