// Fonction déclencher au chargement de la page et démarre à partir de la classe "grille"
$('.grille').ready(function () {

    // Vérification en fonction du nombre de résultat afficher

    if($('#container_fluid div ul li').length > 5){
        $('#container_fluid div ul').removeClass('columns-2')
        $('#container_fluid div ul').addClass('columns-4');
    } else if ($('#container_fluid div ul li').length <= 4){
        $('#container_fluid div ul').addClass('columns-2');
    }

    // Affichage de 12 wignet maxium

    $('#container_fluid div ul li:gt(11)').hide();

    $('.container-fluid div ul.columns-3 li:gt(8)').hide();
});

$('#modifClient').ready(function () {

    // Vérification créneaux en fonction de la sélectionner

    jQuery('select[name="BX_sal[]"]').on('change', function () {

        //Fonction récupérant les paramètres de l'url
        function $_GET(param) {
            var vars = {};
            window.location.href.replace( location.hash, '' ).replace(
                /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
                function( m, key, value ) { // callback
                    vars[key] = value !== undefined ? value : '';
                }
            );

            if ( param ) {
                return vars[param] ? vars[param] : null;
            }
            return vars;
        }
        var dataDate = $_GET('dateSeminaire');

        $('.idReservation').each(function () {
            $dateReserver = $(this).attr('id');

            if(dataDate == $dateReserver){
                $(this).removeClass('hide');
            } else {
                $(this).addClass('hide');
            }
        });

    });

    jQuery('select[name="BX_debut[]"]').on('change', function () {

        $debutTime = $(this).val();

        // Fonction empêchant la sélection de créneaux plus tôt que celui de la liste début
        $('select[name="BX_fin[]"] option').each(function () {
            $finTime = $(this).val();
            if($debutTime >= $finTime){
                $(this).attr('disabled', true);
            } else {
                $(this).removeAttr('disabled');
            }
        });

    });
});

