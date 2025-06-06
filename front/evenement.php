<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';

$database = new Database();
$db = $database->getConnection();
$evenement = new Evenement($db);

$id = $_GET['id'] ?? 0;
$event = $evenement->lireUn($id);

if (!$event) {
    header("Location: index.php");
    exit();
}

$artistes = $evenement->getArtistes($id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['nom']) ?> - Event Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .event-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .artist-card {
            transition: transform 0.2s;
        }
        .artist-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-calendar-alt text-primary"></i> Event Manager
            </a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-arrow-left"></i> Retour au calendrier
                </a>
            </div>
        </div>
    </nav>

    <div class="event-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 mb-3"><?= htmlspecialchars($event['nom']) ?></h1>
                    <div class="d-flex flex-wrap gap-3 mb-3">
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-calendar"></i> 
                            <?= date('d/m/Y à H:i', strtotime($event['date_debut'])) ?>
                        </span>
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-map-marker-alt"></i> 
                            <?= htmlspecialchars($event['lieu']) ?>
                        </span>
                        <span class="badge bg-<?= 
                            $event['statut'] === 'planifie' ? 'primary' : 
                            ($event['statut'] === 'en_cours' ? 'warning' : 
                            ($event['statut'] === 'termine' ? 'success' : 'danger')) 
                        ?> px-3 py-2">
                            <?= ucfirst(str_replace('_', ' ', $event['statut'])) ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <?php if ($event['statut'] === 'planifie'): ?>
                    <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#billetterieModal">
                        <i class="fas fa-ticket-alt"></i> Réserver
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title"><i class="fas fa-info-circle text-primary"></i> Description</h3>
                        <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-clock text-primary"></i> Horaires</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Début :</strong> <?= date('d/m/Y à H:i', strtotime($event['date_debut'])) ?></li>
                                    <?php if ($event['date_fin']): ?>
                                    <li><strong>Fin :</strong> <?= date('d/m/Y à H:i', strtotime($event['date_fin'])) ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-info text-primary"></i> Informations pratiques</h5>
                                <ul class="list-unstyled">
                                    <?php if ($event['capacite']): ?>
                                    <li><strong>Capacité :</strong> <?= $event['capacite'] ?> places</li>
                                    <?php endif; ?>
                                    <li><strong>Prix :</strong> 
                                        <?= $event['prix'] > 0 ? number_format($event['prix'], 2) . '€' : 'Gratuit' ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <?php if (!empty($artistes)): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-users"></i> Artistes participants</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($artistes as $artiste): ?>
                        <div class="card artist-card mb-3">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-1">
                                    <?= htmlspecialchars($artiste['prenom'] . ' ' . $artiste['nom']) ?>
                                </h6>
                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-music"></i> <?= htmlspecialchars($artiste['specialite']) ?>
                                        <?php if ($artiste['role']): ?>
                                        <br><i class="fas fa-star"></i> <?= htmlspecialchars($artiste['role']) ?>
                                        <?php endif; ?>
                                    </small>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Billetterie -->
    <div class="modal fade" id="billetterieModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-ticket-alt"></i> Réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6><?= htmlspecialchars($event['nom']) ?></h6>
                        <small><?= date('d/m/Y à H:i', strtotime($event['date_debut'])) ?> - <?= htmlspecialchars($event['lieu']) ?></small>
                    </div>
                    
                    <form id="formBilletterie">
                        <div class="mb-3">
                            <label class="form-label">Nombre de places *</label>
                            <select class="form-select" name="quantite" id="quantite" required>
                                <option value="1">1 place</option>
                                <option value="2">2 places</option>
                                <option value="3">3 places</option>
                                <option value="4">4 places</option>
                                <option value="5">5 places</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required 
                                   placeholder="votre@email.com">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nom complet *</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        
                        <div class="alert alert-success">
                            <strong>Total à payer : <span id="totalPrix"><?= number_format($event['prix'], 2) ?></span>€</strong>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="formBilletterie" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Confirmer la réservation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calcul dynamique du prix total
        document.getElementById('quantite').addEventListener('change', function() {
            const prixUnitaire = <?= $event['prix'] ?>;
            const total = (this.value * prixUnitaire).toFixed(2);
            document.getElementById('totalPrix').textContent = total;
        });
        
        // Soumission du formulaire
        document.getElementById('formBilletterie').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Réservation confirmée ! Un email de confirmation vous sera envoyé.');
            bootstrap.Modal.getInstance(document.getElementById('billetterieModal')).hide();
        });
    </script>
</body>
</html>