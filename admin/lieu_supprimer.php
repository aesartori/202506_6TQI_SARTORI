<?php
require_once '../config/database.php';
require_once '../classes/Venue.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$venue = new Venue($db);
$evenement = new Evenement($db);

$id = $_GET['id'] ?? 0;
$lieu = $venue->lireUn($id);

if (!$lieu) {
    header("Location: lieux.php?error=not_found");
    exit();
}

// Récupérer les événements liés
$evenementsLies = $evenement->lireParLieu($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
        try {
            // Supprimer d'abord les événements liés si confirmé
            if (!empty($evenementsLies) && isset($_POST['supprimer_evenements'])) {
                foreach ($evenementsLies as $evt) {
                    $evenement->supprimer($evt['id_evenement']);
                }
            }

            if ($venue->supprimer($id)) {
                header("Location: lieux.php?success=suppression");
                exit();
            }
        } catch (PDOException $e) {
            $error = "Erreur : Impossible de supprimer le lieu car il est lié à des événements.";
        }
    } else {
        header("Location: lieux.php");
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
                    <h5><?= htmlspecialchars($lieu['nom']) ?></h5>
                    <ul class="list-unstyled">
                        <li><strong>Type :</strong> <?= htmlspecialchars($lieu['type']) ?></li>
                        <li><strong>Adresse :</strong> <?= htmlspecialchars($lieu['adresse']) ?></li>
                    </ul>
                </div>
                <?php if ($lieu['photo']): ?>
                <div class="col-md-6">
                    <img src="../uploads/<?= htmlspecialchars($lieu['photo']) ?>" 
                         alt="Photo du lieu" class="img-fluid rounded">
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($evenementsLies)): ?>
            <div class="alert alert-warning">
                <h5><i class="fas fa-calendar-times"></i> Événements associés</h5>
                <p>Ce lieu est lié à <?= count($evenementsLies) ?> événement(s) :</p>
                <ul>
                    <?php foreach ($evenementsLies as $evt): ?>
                    <li>
                        <?= htmlspecialchars($evt['titre']) ?> 
                        (<?= date('d/m/Y', strtotime($evt['date_heure'])) ?>)
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supprimer_evenements" 
                           id="supprimerEvenements" required>
                    <label class="form-check-label" for="supprimerEvenements">
                        Je confirme la suppression de tous les événements associés
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
                    <a href="lieux.php" class="btn btn-lg btn-secondary">
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
