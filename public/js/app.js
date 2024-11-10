document.addEventListener('DOMContentLoaded', function() {
    // Ocultar mensaje de error después de 3 segundos
    var alert = document.getElementById('error-alert');
    if (alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 3000);
    }

    // Filtrar productos en la tabla
    document.getElementById('search').addEventListener('input', function() {
        const input = this.value.toLowerCase();
        const rows = document.querySelectorAll('#productTableBody tr');

        rows.forEach(row => {
            const productName = row.children[1].textContent.toLowerCase();
            if (productName.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    $(document).ready(function() {
        $('.example').select2();
    });
});