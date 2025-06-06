<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);

$evenements = $evenement->lire();

// Conversion pour FullCalendar
$events_json = [];
foreach ($evenements as $evt) {
    $events_json[] = [
        'id' => $evt['id'],
        'title' => $evt['nom'],
        'start' => $evt['date_debut'],
        'end' => $evt['date_fin'],
        'description' => $evt['description'],
        'lieu' => $evt['lieu'],
        'prix' => $evt['prix'],
        'statut' => $evt['statut'],
        'color' => $evt['statut'] === 'annule' ? '#dc3545' : ($evt['statut'] === 'termine' ? '#6c757d' : '#0d6efd')
    ];
}
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
        .fc-event { cursor: pointer; transition: transform 0.2s; }
        .fc-event:hover { transform: scale(1.02); }
        .event-badge { font-size: 0.8em; }
        .event-card { transition: transform 0.2s; }
        .event-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt text-primary"></i> Event Manager
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../admin/index.php">
                    <i class="fas fa-cog"></i> Administration
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar"></i> Calendrier des événements</h5>
                    </div>
                    <div class="card-body p-3">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Prochains événements</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        $prochains_evenements = array_filter($evenements, function($evt) {
                            return strtotime($evt['date_debut']) > time() && $evt['statut'] !== 'annule';
                        });
                        usort($prochains_evenements, function($a, $b) {
                            return strtotime($a['date_debut']) - strtotime($b['date_debut']);
                        });
                        ?>
                        
                        <?php if (empty($prochains_evenements)): ?>
                        <p class="text-muted text-center">Aucun événement à venir</p>
                        <?php else: ?>
                        <?php foreach (array_slice($prochains_evenements, 0, 5) as $evt): ?>
                        <div class="card event-card mb-3 border-start border-4 border-primary">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1"><?= htmlspecialchars($evt['nom']) ?></h6>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar-day"></i> 
                                            <?= date('d/m/Y à H:i', strtotime($evt['date_debut'])) ?>
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($evt['lieu']) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?= 
                                        $evt['statut'] === 'planifie' ? 'primary' : 
                                        ($evt['statut'] === 'en_cours' ? 'warning' : 'success') 
                                    ?> event-badge ms-2">
                                        <?= ucfirst(str_replace('_', ' ', $evt['statut'])) ?>
                                    </span>
                                </div>
                                
                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if ($evt['prix'] > 0): ?>
                                        <span class="text-success fw-bold">
                                            <i class="fas fa-euro-sign"></i> <?= number_format($evt['prix'], 2) ?>
                                        </span>
                                        <?php else: ?>
                                        <span class="text-success fw-bold">
                                            <i class="fas fa-gift"></i> Gratuit
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="evenement.php?id=<?= $evt['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-ticket-alt"></i> Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card shadow mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-2">
                                <div class="border-end">
                                    <h4 class="text-primary"><?= count($evenements) ?></h4>
                                    <small class="text-muted">Total événements</small>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <h4 class="text-success"><?= count($prochains_evenements) ?></h4>
                                <small class="text-muted">À venir</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: <?= json_encode($events_json) ?>,
                eventDidMount: function(info) {
                    // Ajouter des tooltips
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