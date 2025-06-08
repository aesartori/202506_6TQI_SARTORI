<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);

$tri = $_GET['tri'] ?? 'date_asc';
$evenements = $evenement->getProchains(100);

usort($evenements, function($a, $b) use ($tri) {
    if ($tri === 'date_asc') return strtotime($a['date_heure']) <=> strtotime($b['date_heure']);
    if ($tri === 'date_desc') return strtotime($b['date_heure']) <=> strtotime($a['date_heure']);
    if ($tri === 'prix_asc') return $a['prix'] <=> $b['prix'];
    if ($tri === 'prix_desc') return $b['prix'] <=> $a['prix'];
    return 0;
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #121212;
            --surface-dark: #1E1E1E;
            --text-primary: #E0E0E0;
            --text-secondary: #B0B0B0;
            --accent-color: #BB86FC;
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            background: var(--bg-dark);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
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
        
        /* Cartes événements */
        .event-card {
            background: var(--surface-dark);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            transition: transform 0.2s;
            overflow: hidden;
            height: 100%;
        }
        
        .event-card:hover {
            transform: translateY(-4px) scale(1.01);
        }
        
        .event-image {
            height: 180px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .event-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            background: var(--accent-color);
            color: var(--bg-dark);
            padding: 5px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .card-title {
            color: var(--accent-color);
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .card-text {
            color: var(--text-secondary);
            font-size: 0.96rem;
        }
        
        .btn-primary {
            background: var(--accent-color);
            border: none;
            color: var(--bg-dark);
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background: #9D67E7;
            color: white;
        }
        
        /* Barre de tri */
        .sort-bar {
            background: #181828;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        
        .sort-bar label {
            color: var(--text-secondary);
            margin-right: 10px;
        }
        
        .sort-bar select {
            background: #232338;
            color: var(--text-primary);
            border: 1px solid #333;
            border-radius: 8px;
            padding: 6px 12px;
        }
        
        /* Header */
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            flex-wrap: wrap;
        }
        
        .header-title {
            color: var(--accent-color);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        @media (max-width: 600px) {
            .header-row {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .sort-bar {
                width: 100%;
                justify-content: flex-start;
                margin-top: 1rem;
            }
        }
        
        /* Footer */
        footer {
            background-color: #181828;
            color: var(--text-secondary);
            padding: 2rem 0;
            margin-top: auto;
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

    <main class="container pb-5">
        <div class="header-row">
            <h1 class="header-title">Tous les événements</h1>
            <form class="sort-bar mb-0" method="get">
                <label for="tri" class="form-label mb-0">Trier par :</label>
                <select name="tri" id="tri" onchange="this.form.submit()">
                    <option value="date_asc" <?= $tri === 'date_asc' ? 'selected' : '' ?>>Date croissante</option>
                    <option value="date_desc" <?= $tri === 'date_desc' ? 'selected' : '' ?>>Date décroissante</option>
                    <option value="prix_asc" <?= $tri === 'prix_asc' ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="prix_desc" <?= $tri === 'prix_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                </select>
            </form>
        </div>
        <div class="row g-4">
            <?php if(empty($evenements)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Aucun événement à venir.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($evenements as $evt): 
                    $imagePath = !empty($evt['image']) ? "../uploads/" . $evt['image'] : "https://source.unsplash.com/random/800x600?event";
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="event-image" style="background-image: url('<?= $imagePath ?>')">
                            <div class="event-badge">
                                <?= $evt['prix'] > 0 ? number_format($evt['prix'], 2).'€' : 'Gratuit' ?>
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
    </main>

    <footer>
        <div class="container text-center">
            <p class="mb-0">
                © 2025 EventManager v2.0.0. Tous droits réservés.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
