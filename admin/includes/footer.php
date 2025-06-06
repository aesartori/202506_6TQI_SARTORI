    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmerSuppression(element, type) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce ' + type + ' ?')) {
                window.location.href = element.href;
            }
            return false;
        }

        // Fermer automatiquement les alertes après 5 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
