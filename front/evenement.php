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

$imagePath = !empty($event['image']) ? "../uploads/" . $event['image'] : "https://source.unsplash.com/random/1200x600?concert";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['titre']) ?> - Event Manager v2.0.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #121212;
            --surface-dark: #1E1E1E;
            --surface-darker: #181828;
            --text-primary: #E0E0E0;
            --text-secondary: #B0B0B0;
            --accent-color: #BB86FC;
            --accent-gradient: linear-gradient(45deg, #BB86FC, #9D67E7);
        }
        html, body { height: 100%; }
        body {
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main { flex: 1; }
        /* Navbar */
        .navbar {
            background: var(--surface-dark) !important;
        }
        
        .navbar .navbar-brand {
            color: var(--accent-color) !important;
            font-weight: 600;
            font-size: 1.4rem;
            transition: background 0.3s ease, color 0.3s ease;
            padding: 4px 8px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .navbar .navbar-brand:hover {
            background: linear-gradient(45deg, var(--accent-color), #9D67E7) !important;
            color: #fff !important;
            text-decoration: none;
        }
        
        .navbar .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: var(--accent-color) !important;
            background: rgba(187, 134, 252, 0.1);
        }
        /* Bannière événement */
        .event-hero {
            background: url('<?= $imagePath ?>') center/cover no-repeat;
            border-radius: 16px;
            min-height: 340px;
            margin: 2rem auto 2.5rem auto;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            max-width: 1100px;
            animation: fadein 1.2s;
        }
        @keyframes fadein { from { opacity:0; } to { opacity:1; } }
        .event-hero::after {
            content: "";
            position: absolute; left:0; top:0; right:0; bottom:0;
            background: linear-gradient(180deg,rgba(24,24,40,0.10) 0%,rgba(24,24,40,0.98) 100%);
        }
        .event-hero-content {
            position: relative;
            z-index: 2;
            padding: 2rem 2rem 1.5rem 2rem;
            color: #fff;
        }
        .event-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: var(--accent-color);
            text-shadow: 0 2px 12px rgba(0,0,0,0.18);
        }
        .event-meta {
            font-size: 1.1rem;
            color: #fff;
            opacity: 0.92;
            margin-bottom: 1rem;
        }
        .event-main {
            max-width: 1100px;
            margin: 0 auto 2.5rem auto;
        }
        .event-section {
            background: var(--surface-dark);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            margin-bottom: 1.5rem;
            padding: 1.2rem 1.5rem;
            animation: fadein 1.2s;
        }
        .event-section h5 {
            color: var(--accent-color);
            font-size: 1.1rem;
            font-weight: 600;
        }
        .event-section .badge {
            background: var(--accent-color);
            color: var(--bg-dark);
            font-weight: 600;
        }
        .ticket-form {
            background: var(--surface-dark);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            padding: 1.5rem 1.2rem;
            animation: fadein 1.2s;
        }
        .ticket-form h4 {
            color: var(--accent-color);
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
        }
        .btn-primary {
            background: var(--accent-gradient);
            border: none;
            color: var(--bg-dark);
            font-weight: 600;
            transition: box-shadow 0.2s, background 0.2s;
            box-shadow: 0 2px 12px rgba(187,134,252,0.10);
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #9D67E7, #BB86FC);
            color: #fff;
            box-shadow: 0 4px 24px rgba(187,134,252,0.18);
        }
        .btn-outline-primary {
            border-color: var(--accent-color);
            color: var(--accent-color);
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: var(--accent-color);
            color: var(--bg-dark);
        }
        .alert {
            border-radius: 12px;
        }
        .faq-section {
            background: var(--surface-dark);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            padding: 1.2rem 1.5rem;
        }
        .faq-title {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .accordion-button {
            background: var(--surface-dark);
            color: var(--text-primary);
            font-weight: 500;
        }
        .accordion-button:not(.collapsed) {
            background: var(--accent-gradient);
            color: #fff;
        }
        .accordion-button:focus {
            box-shadow: none;
        }
        .accordion-body {
            background: var(--surface-darker);
            color: var(--text-primary);
        }
        /* Footer identique à l'index */
        footer {
            background: var(--surface-darker);
            color: var(--text-secondary);
            margin-top: auto;
            padding: 1.5rem;
        }
        @media (max-width: 991px) {
            .event-main { padding: 0 0.5rem; }
            .event-hero-content { padding: 2rem 1rem 1.5rem 1rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt"></i>
                <span class="ms-2">Event Manager</span>
            </a>
            <div class="d-flex align-items-center">
                <a class="nav-link px-3" href="events.php">
                    <i class="fas fa-list"></i> Tous les événements
                </a>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="nav-link px-3" href="../admin/index.php">
                    <i class="fas fa-cog"></i>
                </a>
            </div>
        </div>
    </nav>

    <main>
        <div class="event-hero">
            <div class="event-hero-content">
                <h1 class="event-title"><?= htmlspecialchars($event['titre']) ?></h1>
                <div class="event-meta">
                    <i class="fas fa-calendar-day"></i> <?= date('d/m/Y H:i', strtotime($event['date_heure'])) ?>
                    <?php if ($venueInfo): ?>
                        &nbsp; | &nbsp;<i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($venueInfo['nom']) ?>
                    <?php endif; ?>
                </div>
                <a href="#reservation" class="btn btn-primary btn-lg mt-2">
                    <i class="fas fa-ticket-alt"></i> Réservez votre place maintenant
                </a>
            </div>
        </div>

        <div class="event-main row g-4">
            <div class="col-lg-8">
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

                <div class="event-section">
                    <h5>Description</h5>
                    <div><?= nl2br(htmlspecialchars($event['description'])) ?></div>
                </div>
                <?php if ($venueInfo): ?>
                <div class="event-section">
                    <h5><i class="fas fa-map-marker-alt"></i> Lieu</h5>
                    <div>
                        <strong><?= htmlspecialchars($venueInfo['nom']) ?></strong>
                        <?php if ($venueInfo['type']): ?>
                            <span class="badge ms-2"><?= htmlspecialchars($venueInfo['type']) ?></span>
                        <?php endif; ?><br>
                        <?php if ($venueInfo['adresse']): ?>
                            <span><?= htmlspecialchars($venueInfo['adresse']) ?></span><br>
                        <?php endif; ?>
                        <?php if ($venueInfo['url']): ?>
                            <a href="<?= htmlspecialchars($venueInfo['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fas fa-external-link-alt"></i> Site web
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($artisteInfo): ?>
                <div class="event-section">
                    <h5><i class="fas fa-user"></i> Artiste</h5>
                    <div>
                        <strong><?= htmlspecialchars($artisteInfo['nom']) ?></strong><br>
                        <?php if ($artisteInfo['url']): ?>
                            <a href="<?= htmlspecialchars($artisteInfo['url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fas fa-external-link-alt"></i> Site web
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- FAQ interactive -->
                <div class="faq-section">
                    <div class="faq-title"><i class="fas fa-question-circle"></i> Questions fréquentes</div>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    Comment puis-je recevoir mon billet ?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Votre billet vous sera envoyé par email immédiatement après la réservation.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    Puis-je annuler ma réservation ?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, contactez-nous au moins 48h avant l'événement pour toute annulation.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    Y a-t-il un parking sur place ?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, un parking est disponible à proximité du lieu de l'événement.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /FAQ -->
            </div>
            <div class="col-lg-4">
                <div class="ticket-form" id="reservation">
                    <h4><i class="fas fa-ticket-alt"></i> Réserver</h4>
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
    </main>

    <footer>
        <div class="container text-center">
            <p class="mb-0">
                © 2025 EventManager v2.0.0. Tous droits réservés.
            </p>
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
