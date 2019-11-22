// Lance la fonction au chargement de la page à partir de l'id "datepicker"
$("#datepicker").ready(function () {

    // Création du calendrier
    $('#datepicker').datepicker({
        altField: "#datepicker",
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate: '+2Y',
        // Action lors de la sélection d'une date
        onSelect: function (dateText) {
            calendar = dateText;

            // Vérification si le champ date(calendrier) n'est pas vide
            if(calendar !== ""){

                // Appel de la fonction Salle()
                Salle();

                //Vérification si la date est égale à une date d'un séminaire
                $('.idReservation').each(function () {
                    $dateReserver = $(this).attr('id');

                    if(calendar == $dateReserver){
                        $(this).removeClass('hide');
                    } else {
                        $(this).addClass('hide');
                    }
                });


            }
        }
    });

    //Fonction Salle() qui permet la sélection de la liste salle
    function Salle() {

        jQuery('select[name="BX_salle[]"]').removeAttr('disabled');

        // Fonction déclencher à chaque changement de sélection dans la liste salle
        $(jQuery('select[name="BX_salle[]"]')).on('change', function () {

            $malibu = $('select[name="BX_salle[]"] option[value="10"]');
            $havana = $('select[name="BX_salle[]"] option[value="11"]');
            $malibuHavana = $('select[name="BX_salle[]"] option[value="9"]');
            $salleSelect = $(this).find('option:selected').text();

            // Vérification si la salle modulable est sélectitonner
            if($salleSelect == "Malibu - Havana Club"){

                $malibu.attr('disabled', true);
                $havana.attr('disabled',true);
            } else {
                $malibu.removeAttr('disabled');
                $havana.removeAttr('disabled');
            }

            // Vérification si l'une des deux salles modulables sont sélectionnées
            if($salleSelect == "Malibu" || $salleSelect== "Havana Club"){

                $malibuHavana.attr('disabled', true);
            } else {
                $malibuHavana.removeAttr('disabled');
            }

            // Vérification si la sélection de la liste salle n'est pas vide
            if(jQuery('select[name="BX_salle[]"]').find('option:selected').text() != ""){
                // Appel de la fonction Debut()
                Debut();

                // Fonction déclencer à chaque changement de sélection de la liste début
                jQuery('select[name="BX_debut[]"]').on('change', function () {

                    // Appel de la fonction Fin()
                    Fin();

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

                // Fonction déclencher à chaque changement de sélection de liste fin
                jQuery('select[name="BX_fin[]"]').on('change', function () {

                    // Appel de la function AjoutCreneaux()
                    AjoutCrenaux();

                });
            }
        });
    }

    //Fonction Debut() qui permet la sélection de la liste début
    function Debut() {
        jQuery('select[name="BX_debut[]"]').removeAttr('disabled');
    }

    // Fonction Fin() qui permet la sélection de la liste fin
    function Fin() {
        jQuery('select[name="BX_fin[]"]').removeAttr('disabled');
    }

    // Fonction AjoutCreneaux() qui permet l'ajout d'une jusqu'à 4 créneaux
    function AjoutCrenaux() {
        $('#AjoutCreneau').removeAttr('disabled');
    }
});
