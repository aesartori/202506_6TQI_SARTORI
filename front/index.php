<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';
require_once '../classes/Venue.php';
require_once '../classes/Artiste.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);
$venue = new Venue($db);
$artiste = new Artiste($db);

$evenements = $evenement->getProchains(12);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager v1 - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .event-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }
        .event-price {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(255,255,255,0.9);
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .navbar {
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
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

    <div class="container py-5">
        <h1 class="mb-5 text-center">Prochains événements</h1>
        
        <div class="row g-4">
            <?php if(empty($evenements)): ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Aucun événement à venir pour le moment.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($evenements as $evt): 
                    $venueInfo = $venue->lireUn($evt['id_venue']);
                    $artisteInfo = $artiste->lireUn($evt['id_artiste']);
                    $imagePath = !empty($evt['image']) ? "../uploads/" . $evt['image'] : "https://via.placeholder.com/800x400?text=Événement";
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card event-card">
                        <div class="event-image" style="background-image: url('<?= $imagePath ?>');">
                            <div class="event-price">
                                <?php if($evt['prix'] > 0): ?>
                                    <?= number_format($evt['prix'], 2) ?> €
                                <?php else: ?>
                                    Gratuit
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($evt['titre']) ?></h5>
                            <p class="card-text">
                                <i class="fas fa-calendar-day"></i> <?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?><br>
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($evt['venue_nom'] ?? 'Lieu à confirmer') ?><br>
                                <?php if($evt['artiste_nom']): ?>
                                <i class="fas fa-user"></i> <?= htmlspecialchars($evt['artiste_nom']) ?>
                                <?php endif; ?>
                            </p>
                            <a href="evenement.php?id=<?= $evt['id_evenement'] ?>" class="btn btn-primary w-100">
                                <i class="fas fa-ticket-alt"></i> Réserver
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">© 2025 Event Manager v1. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
