</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmerSuppression(element, type) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce ' + type + ' ?')) {
                window.location.href = element.href;
            }
            return false;
        }
    </script>
</body>
</html>