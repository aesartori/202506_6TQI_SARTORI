<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);

$evenements = $evenement->getProchains(30);

// Conversion pour FullCalendar (structure corrigée)
$events_json = [];
foreach ($evenements as $evt) {
    $events_json[] = [
        'id' => $evt['id_evenement'],
        'title' => $evt['titre'],
        'start' => $evt['date_heure'],
        'description' => $evt['description'],
        'lieu' => $evt['venue_nom'] ?? 'Non défini',
        'prix' => $evt['prix'],
        'color' => '#BB86FC'
    ];
}

// Prochains événements pour suggestions (aléatoires)
$prochains_evenements = array_filter($evenements, function($evt) {
    return strtotime($evt['date_heure']) > time();
});
shuffle($prochains_evenements); // Mélange aléatoire pour avoir 3 événements différents à chaque rafraîchissement
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager - Calendrier des événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
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
        
        /* Cartes suggestions */
        .calendar-section, .suggestions-section { 
            background: var(--surface-dark); 
            border-radius: 16px; 
            box-shadow: 0 4px 24px rgba(0,0,0,0.1); 
            padding: 1.5rem;
        }
        
        .event-suggestion-card {
            background: #181828;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            margin-bottom: 1.5rem;
            border: 1px solid #232338;
            transition: transform 0.18s;
        }
        
        .event-suggestion-card:hover { 
            transform: scale(1.02);
        }
        
        .event-image { 
            height: 90px; 
            width: 90px; 
            border-radius: 10px; 
            object-fit: cover; 
        }
        
        .event-title { 
            font-weight: 600; 
            color: var(--accent-color);
        }
        
        .event-meta { 
            color: var(--text-secondary); 
            font-size: 0.95rem;
        }
        
        .btn-accent { 
            background: var(--accent-color); 
            color: #181828; 
            border: none; 
            font-weight: 600;
        }
        
        .btn-accent:hover { 
            background: #9D67E7; 
            color: white;
        }
        
        /* FullCalendar Dark Mode */
        .fc-theme-standard .fc-scrollgrid, 
        .fc-theme-standard td, 
        .fc-theme-standard th { 
            background: #181828; 
            color: var(--text-primary);
            border-color: #333;
        }
        
        .fc-toolbar-title { 
            color: var(--accent-color);
        }
        
        .fc-daygrid-day-number { 
            color: var(--accent-color);
        }
        
        .fc-event, .fc-event-dot { 
            background: var(--accent-color); 
            border: none;
        }
        
        .fc-button-primary {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .fc-button-primary:hover {
            background: #9D67E7;
            border-color: #9D67E7;
        }
        
        #calendar { 
            min-height: 500px; 
        }
        
        .fc-event { 
            cursor: pointer; 
            transition: transform 0.2s; 
        }
        
        .fc-event:hover { 
            transform: scale(1.02); 
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
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="calendar-section mb-4">
                    <h2 class="h5 mb-4" style="color: var(--accent-color);">
                        <i class="fas fa-calendar me-2"></i>Calendrier des événements
                    </h2>
                    <div id="calendar"></div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="suggestions-section mb-4">
                    <h2 class="h5 mb-4" style="color: var(--accent-color);">
                        <i class="fas fa-bolt me-2"></i>Suggestions
                    </h2>
                    
                    <?php if (empty($prochains_evenements)): ?>
                        <div class="text-secondary">Aucun événement à suggérer.</div>
                    <?php else: ?>
                        <?php foreach (array_slice($prochains_evenements, 0, 3) as $evt): 
                            $imagePath = !empty($evt['image']) ? "../uploads/" . $evt['image'] : "https://source.unsplash.com/random/200x200?event";
                        ?>
                        <div class="event-suggestion-card d-flex align-items-center p-3 mb-3">
                            <img src="<?= $imagePath ?>" class="event-image me-3" alt="event">
                            <div class="flex-grow-1">
                                <div class="event-title"><?= htmlspecialchars($evt['titre']) ?></div>
                                <div class="event-meta mb-1">
                                    <i class="fas fa-calendar-day"></i> <?= date('d/m/Y H:i', strtotime($evt['date_heure'])) ?><br>
                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($evt['venue_nom'] ?? 'Lieu à confirmer') ?>
                                </div>
                                <a href="evenement.php?id=<?= $evt['id_evenement'] ?>" class="btn btn-accent btn-sm mt-1 w-100">
                                    <i class="fas fa-ticket-alt"></i> Réserver
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: <?= json_encode($events_json) ?>,
                eventDidMount: function(info) {
                    info.el.setAttribute('title', 
                        info.event.title + '\n' + 
                        info.event.extendedProps.lieu + '\n' + 
                        (info.event.extendedProps.prix > 0 ? 
                            info.event.extendedProps.prix + '€' : 'Gratuit')
                    );
                },
                eventClick: function(info) {
                    window.location.href = 'evenement.php?id=' + info.event.id;
                },
                dayMaxEvents: 3,
                moreLinkClick: 'popover'
            });
            calendar.render();
        });
    </script>
</body>
</html>
