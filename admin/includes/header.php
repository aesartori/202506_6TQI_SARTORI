<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Manager v1 - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .table th { background-color: #495057; color: white; }
        .btn-action { margin: 2px; }
        .status-badge { font-size: 0.8em; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt"></i> Event Manager v1
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'evenements.php' ? 'active' : '' ?>" href="evenements.php">
                            <i class="fas fa-calendar"></i> Événements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'artistes.php' ? 'active' : '' ?>" href="artistes.php">
                            <i class="fas fa-users"></i> Artistes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === 'taches.php' ? 'active' : '' ?>" href="taches.php">
                            <i class="fas fa-tasks"></i> Tâches
                        </a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <a class="nav-link" href="../front/index.php" target="_blank">
                        <i class="fas fa-eye"></i> Voir le site
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid mt-4">