<?php
require_once '../config/database.php';
require_once '../classes/Artiste.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$artiste = new Artiste($db);
$evenement = new Evenement($db);

$id = $_GET['id'] ?? 0;
$artistData = $artiste->lireUn($id);

if (!$artistData) {
    header("Location: artistes.php?error=not_found");
    exit();
}

// Récupérer les événements liés à l'artiste
$evenementsLies = $evenement->lireParArtiste($id); // Tu dois créer cette méthode

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
        try {
            // Si tu veux gérer la suppression en cascade, adapte ici
            // (par défaut, la contrainte de clé étrangère bloque la suppression s'il y a des événements)
            if (!empty($evenementsLies) && isset($_POST['supprimer_evenements']) && $_POST['supprimer_evenements'] === 'oui') {
                foreach ($evenementsLies as $evt) {
                    $evenement->supprimer($evt['id_evenement']);
                }
            }
            // Maintenant, supprimer l'artiste
            if ($artiste->supprimer($id)) {
                header("Location: artistes.php?success=suppression");
                exit();
            } else {
                $error = "Erreur lors de la suppression.";
            }
        } catch (PDOException $e) {
            $error = "Erreur : Impossible de supprimer l'artiste car il est lié à des événements.";
        }
    } else {
        header("Location: artistes.php");
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
                    <h5><?= htmlspecialchars($artistData['nom']) ?></h5>
                    <ul class="list-unstyled">
                        <li><strong>URL :</strong>
                            <?php if (!empty($artistData['url'])): ?>
                                <a href="<?= htmlspecialchars($artistData['url']) ?>" target="_blank" style="color: var(--accent-color);">
                                    <?= htmlspecialchars($artistData['url']) ?>
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-secondary);">-</span>
                            <?php endif; ?>
                        </li>
                        <li><strong>Photo :</strong> <?= htmlspecialchars($artistData['photo'] ?? '-') ?></li>
                    </ul>
                </div>
                <?php if (!empty($artistData['photo'])): ?>
                <div class="col-md-6">
                    <img src="../uploads/<?= htmlspecialchars($artistData['photo']) ?>" 
                         alt="Photo de l'artiste" class="img-fluid rounded" style="max-width: 200px;">
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($evenementsLies)): ?>
            <div class="alert alert-warning">
                <h5><i class="fas fa-calendar"></i> Événements associés</h5>
                <p>Cet artiste est lié à <?= count($evenementsLies) ?> événement(s) :</p>
                <ul>
                    <?php foreach ($evenementsLies as $evt): ?>
                    <li>
                        <?= htmlspecialchars($evt['titre']) ?> 
                        (<?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?>)
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="supprimer_evenements" 
                           id="supprimerEvenements" required>
                    <label class="form-check-label" style="color: var(--text-primary);">
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
                    <a href="artistes.php" class="btn btn-lg btn-secondary btn-action">
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
