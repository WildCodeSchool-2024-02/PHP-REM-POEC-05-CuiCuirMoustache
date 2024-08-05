$(document).ready(function() {
    console.log("filter");
    function filterProducts() {
        let minPrice = parseFloat($('input[name="min-price"]').val()) || 0;
        let maxPrice = parseFloat($('input[name="max-price"]').val()) || Infinity;
        let search = $('input[id="searchUser"]').val() || "";
        
        let selectedSizes = $('input[name="size-filter"]:checked').map(function() {
            return $(this).val();
        }).get();


        let sortBy = $('input[name="myfilter_radio"]:checked').val();

        $('.elements-filtering > div').each(function() {
            let product = $(this);
            let productPrice = parseFloat(product.find('.total span:first-child').text().replace('€', '').trim());
            let productName = product.find('h4').text();
            let showProduct = true;

            console.log("produit :");

            // Filtre par prix
            if (productPrice < minPrice || productPrice > maxPrice) {
                showProduct = false;
            }

            // Filtre par taille
            if (selectedSizes.length > 0) {
                let hasSize = selectedSizes.some(size => productName.includes(`- ${size}`));
                console.log(hasSize);
                if (!hasSize) {
                    showProduct = false;
                }
            }

             // Filtre par recherche
             if (search.length > 0) {

                productNameU = productName.toUpperCase();
                searchU = search.toUpperCase();
                let hasSearch = productNameU.includes(`${searchU}`);
                if (!hasSearch) {
                    showProduct = false;
                }
            }

            console.log(showProduct);
            // Affichage ou masquage du produit
            if (showProduct) {
                product.attr('style',"width: 300px;height:480px;");
            } else {
                product.attr('style',"display: none!important");
            }
        });

        // Tri des produits
        sortProducts(sortBy);
    }

    function sortProducts(criteria) {
        let products = $('.elements-filtering > div');

        products.sort(function(a, b) {
            let valueA, valueB;

            switch(criteria) {
                case 'recent':
                    valueA = new Date($(a).data('date'));
                    valueB = new Date($(b).data('date'));
                    return valueB - valueA; // Plus récent en premier
                case 'old':
                    valueA = new Date($(a).data('date'));
                    valueB = new Date($(b).data('date'));
                    return valueA - valueB; // Plus vieux en premier
                case 'stock':
                    valueA = parseInt($(a).data('stock'));
                    valueB = parseInt($(b).data('stock'));
                    return valueB - valueA; // Plus de stock en premier
                default:
                    return 0; // Ordre par défaut
            }
        });

        // Réorganiser les produits triés dans le DOM
        $('.elements-filtering').html(products);
    }

    // Événements sur les filtres
    $('input[name="min-price"], input[name="max-price"]').on('input', filterProducts);
    $('input[name="size-filter"]').on('change', filterProducts);
    $('input[name="myfilter_radio"]').on('change', filterProducts);
    $('input[id="searchUser"]').on('input', filterProducts);

    // Appel initial pour afficher les produits selon les filtres par défaut
    filterProducts();
});