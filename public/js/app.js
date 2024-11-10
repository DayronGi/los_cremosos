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
        // Filtrado de productos
        $('#saleProductSearch').on('keyup', function() {
            var searchText = $(this).val().toLowerCase(); // Obtén el texto de búsqueda
            var productList = $('#productList');
            
            // Si el campo de búsqueda está vacío, oculta la lista
            if (searchText === '') {
                productList.hide();
            } else {
                // Muestra la lista de productos
                productList.show();
                $('#productList .product-option').each(function() {
                    var productName = $(this).text().toLowerCase(); // Nombre del producto
                    if (productName.indexOf(searchText) === -1) { // Si no coincide, ocultamos
                        $(this).hide();
                    } else {
                        $(this).show(); // Si coincide, lo mostramos
                    }
                });
            }
        });

        // Selección de un producto
        $('#productList').on('click', '.product-option', function() {
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            
            // Colocamos el nombre del producto en el campo de búsqueda
            $('#saleProductSearch').val(productName);
            
            // Establecemos el ID del producto en el campo oculto
            $('#saleProduct').val(productId);
            
            // Ocultamos la lista después de seleccionar un producto
            $('#productList').hide();
        });

        // Opcional: Limpiar el campo de búsqueda y la lista cuando se abre el modal
        $('#addSaleModal').on('show.bs.modal', function() {
            $('#saleProductSearch').val(''); // Limpiar el campo de búsqueda
            $('#productList').hide(); // Asegurarnos de que la lista esté oculta
        });
    });
});