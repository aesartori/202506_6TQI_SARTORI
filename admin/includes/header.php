<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventManager - Admin</title>
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
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--bg-dark);
            color: var(--text-primary);
        }
        main {
            flex: 1;
            padding-bottom: 2rem;
        }
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
        .card, .card-header {
            background: #181828;
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt"></i>
                <span class="ms-2">Event Manager Admin</span>
            </a>
            <div class="d-flex align-items-center">
                <a class="nav-link px-3" href="evenements.php">
                    <i class="fas fa-list"></i> Événements
                </a>
                <a class="nav-link px-3" href="artistes.php">
                    <i class="fas fa-users"></i> Artistes
                </a>
                <a class="nav-link px-3" href="lieux.php">
                    <i class="fas fa-map-marker-alt"></i> Lieux
                </a>
                <a class="nav-link px-3" href="tickets.php">
                    <i class="fas fa-ticket-alt"></i> Tickets
                </a>
            </div>
            <div class="navbar-nav ms-auto">
                <a class="nav-link px-3" href="../../index.html">
                    <i class="fas fa-home"></i> Retour au site
                </a>
            </div>
        </div>
    </nav>
