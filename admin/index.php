<?php
require_once '../config/database.php';
require_once '../classes/Evenement.php';
require_once '../classes/Artiste.php';
require_once '../classes/Tache.php';

$database = new Database();
$db = $database->getConnection();

$evenement = new Evenement($db);
$artiste = new Artiste($db);
$tache = new Tache($db);

$evenements = $evenement->lire();
$artistes = $artiste->lire();
$taches = $tache->lire();

include 'includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?= count($evenements) ?></h4>
                        <p class="mb-0">Événements</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?= count($artistes) ?></h4>
                        <p class="mb-0">Artistes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?= count($taches) ?></h4>
                        <p class="mb-0">Tâches</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?= array_filter($taches, fn($t) => $t['statut'] === 'a_faire') ? count(array_filter($taches, fn($t) => $t['statut'] === 'a_faire')) : 0 ?></h4>
                        <p class="mb-0">Tâches en attente</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Prochains événements</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Lieu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($evenements, 0, 5) as $evt): ?>
                            <tr>
                                <td><?= htmlspecialchars($evt['nom']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($evt['date_debut'])) ?></td>
                                <td><?= htmlspecialchars($evt['lieu']) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $evt['statut'] === 'planifie' ? 'primary' : 
                                        ($evt['statut'] === 'en_cours' ? 'warning' : 
                                        ($evt['statut'] === 'termine' ? 'success' : 'danger')) 
                                    ?> status-badge">
                                        <?= ucfirst(str_replace('_', ' ', $evt['statut'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="evenement_modifier.php?id=<?= $evt['id'] ?>" class="btn btn-sm btn-warning btn-action">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="../front/evenement.php?id=<?= $evt['id'] ?>" 
                                       class="btn btn-sm btn-info btn-action" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-tasks"></i> Tâches urgentes</h5>
            </div>
            <div class="card-body">
                <?php 
                $taches_urgentes = array_filter($taches, function($t) {
                    return $t['priorite'] === 'haute' && $t['statut'] !== 'termine';
                });
                ?>
                <?php if(empty($taches_urgentes)): ?>
                <p class="text-muted">Aucune tâche urgente</p>
                <?php else: ?>
                <?php foreach(array_slice($taches_urgentes, 0, 5) as $tache): ?>
                <div class="border-start border-danger border-4 ps-3 mb-3">
                    <h6 class="mb-1"><?= htmlspecialchars($tache['titre']) ?></h6>
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> <?= date('d/m/Y H:i', strtotime($tache['date_echeance'])) ?>
                        <?php if($tache['evenement_nom']): ?>
                        <br><i class="fas fa-calendar"></i> <?= htmlspecialchars($tache['evenement_nom']) ?>
                        <?php endif; ?>
                    </small>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>