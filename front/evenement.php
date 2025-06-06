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

$id = $_GET['id'] ?? 0;
$event = $evenement->lireUn($id);

if (!$event) {
    header("Location: index.php");
    exit();
}

$venueInfo = $venue->lireUn($event['id_venue']);
$artisteInfo = $artiste->lireUn($event['id_artiste']);
$message = '';
$error = '';

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nom_complet']) || !isset($_POST['email']) || !isset($_POST['quantite'])) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            if ($ticket->creer($id, $_POST['nom_complet'], $_POST['email'], $_POST['quantite'], $event['prix'])) {
                $message = "Votre réservation a été enregistrée avec succès !";
            } else {
                $error = "Erreur lors de l'enregistrement de votre réservation.";
            }
        } catch (Exception $e) {
            $error = "Erreur: " . $e->getMessage();
        }
    }
}

$imagePath = !empty($event['image']) ? "../uploads/" . $event['image'] : "https://via.placeholder.com/1200x600?text=Événement";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['titre']) ?> - Event Manager v1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .event-banner {
            height: 320px;
            background-size: cover;
            background-position: center;
            border-radius: 16px;
            margin-bottom: 32px;
            position: relative;
        }
        .event-title-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.55);
            color: #fff;
            padding: 24px 32px;
            border-radius: 0 0 16px 16px;
        }
        .event-title-overlay h1 {
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
        }
        .event-title-overlay .meta {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .event-main {
            margin-bottom: 40px;
        }
        .venue-info, .artist-info {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 24px;
        }
        .ticket-form {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 24px;
        }
        @media (max-width: 991px) {
            .event-main { flex-direction: column; }
            .event-main > .col-lg-8, .event-main > .col-lg-4 { max-width: 100%; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt text-primary"></i> Event Manager v1
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../admin/index.php">
                    <i class="fas fa-cog"></i> Administration
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="event-banner" style="background-image: url('<?= $imagePath ?>');">
            <div class="event-title-overlay">
                <h1><?= htmlspecialchars($event['titre']) ?></h1>
                <div class="meta">
                    <i class="fas fa-calendar-day"></i> <?= date('d/m/Y H:i', strtotime($event['date_heure'])) ?>
                    <?php if ($venueInfo): ?>
                        &nbsp; | &nbsp;<i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($venueInfo['nom']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

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

        <div class="row event-main">
            <div class="col-lg-8 mb-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Description</h4>
                        <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                    </div>
                </div>
                <?php if ($venueInfo): ?>
                <div class="venue-info mb-4">
                    <h5><i class="fas fa-map-marker-alt"></i> Lieu</h5>
                    <p>
                        <strong><?= htmlspecialchars($venueInfo['nom']) ?></strong><br>
                        <?php if ($venueInfo['type']): ?>
                            <span class="badge bg-secondary"><?= htmlspecialchars($venueInfo['type']) ?></span><br>
                        <?php endif; ?>
                        <?php if ($venueInfo['adresse']): ?>
                            <?= htmlspecialchars($venueInfo['adresse']) ?><br>
                        <?php endif; ?>
                        <?php if ($venueInfo['url']): ?>
                            <a href="<?= htmlspecialchars($venueInfo['url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-external-link-alt"></i> Site web
                            </a>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>

                <?php if ($artisteInfo): ?>
                <div class="artist-info">
                    <h5><i class="fas fa-user"></i> Artiste</h5>
                    <p>
                        <strong><?= htmlspecialchars($artisteInfo['nom']) ?></strong><br>
                        <?php if ($artisteInfo['url']): ?>
                            <a href="<?= htmlspecialchars($artisteInfo['url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-external-link-alt"></i> Site web
                            </a>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <div class="ticket-form">
                    <h4 class="mb-3"><i class="fas fa-ticket-alt"></i> Réserver</h4>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="nom_complet" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom_complet" name="nom_complet" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantite" class="form-label">Nombre de places</label>
                            <select class="form-select" id="quantite" name="quantite" required>
                                <?php for($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prix total</label>
                            <div>
                                <?php if ($event['prix'] > 0): ?>
                                    <span class="fs-5 text-success"><span id="total"><?= number_format($event['prix'], 2) ?></span> €</span>
                                <?php else: ?>
                                    <span class="fs-5 text-success">Gratuit</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-cart"></i> Réserver maintenant
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">© 2025 Event Manager v1. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Met à jour le prix total en fonction de la quantité
        document.addEventListener('DOMContentLoaded', function() {
            var prixUnitaire = <?= (float)$event['prix'] ?>;
            var select = document.getElementById('quantite');
            var total = document.getElementById('total');
            if(select && total) {
                select.addEventListener('change', function() {
                    total.textContent = (prixUnitaire * parseInt(this.value)).toFixed(2);
                });
            }
        });
    </script>
</body>
</html>
