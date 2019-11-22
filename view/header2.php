<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Interface de gestion ChateauForm CNIT</title>

    <!-- Favicon -->
    <link rel="icon" href="/pernod/Logo/logo_chateauform.ico">

    <!-- Javascript -->
    <script type="text/javascript" src="/pernod/js/jquery-3.2.1.min.map"></script>
    <script type="text/javascript" src="/pernod/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/pernod/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/pernod/js/bootstrap.js"></script>
    <script type="text/javascript" src="/pernod/js/bootstrap.min.js"></script>
    <script src="js/Js2.js"></script>
    <script>
        if('serviceWorker' in navigator) {
            navigator.serviceWorker
                .register('/sw.js')
                .then(function() { console.log("Service Worker Registered"); });
        }
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.min.css">
    <link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="/pernod/Css/bootstrap.min.css.map">
    <link rel="stylesheet" href="/pernod/Css/bootstrap-theme.min.css.map">
    <link rel="stylesheet" href="/pernod/Css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/pernod/Css/bootstrap.min.css">
    <link rel="stylesheet" href="/pernod/Css/style.css">
    <link rel="stylesheet" href="/pernod/Css/stylegrid.css">

    <script type="text/javascript">
        // Fonction d'ajout de 4 lignes maximum
        function addRow() {
            var table;
            table = document.getElementById("tableID");
            var rowCount;
            rowCount = table.rows.length;
            if(rowCount < 4){
                var row;
                row = table.insertRow(rowCount);
                row.id = "addRow"+rowCount;
                var colCount = table.rows[0].cells.length;
                for(var i=0; i <colCount; i++) {
                    var newcell = row.insertCell(i);
                    newcell.innerHTML = table.rows[0].cells[i].innerHTML;

                }
            }else{
                alert("Le maximum de créneaux par séminaire est de 4");

            }

            var table = document.getElementById("tableID");
            var rowCount = table.rows.length;
            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null !== chkbox && true === chkbox.checked) {
                    if(rowCount <= 1) {
                        alert("Il est impossible de supprimer davantage de lignes");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
            }

            //Rend cliquable les listes suivantes après chaque restrictions
            $('#tableID tbody #addRow1 td span select[name="BX_fin[]"] option').each(function () {
                $(this).removeAttr('disabled');
            });


            $('#tableID tbody #addRow2 td span select[name="BX_fin[]"] option').each(function () {
                $(this).removeAttr('disabled');
            });

            $('#tableID tbody #addRow3 td span select[name="BX_fin[]"] option').each(function () {
                $(this).removeAttr('disabled');
            });

            // Restriction créneau de la seconde ligne
            $('#tableID tbody #addRow1 td span select[name="BX_debut[]"]').on('change', function () {
                $debutTime2 = $(this).val();

                $('#tableID tbody #addRow1 td span select[name="BX_fin[]"] option').each(function () {
                    $finTime2 = $(this).val();
                    if($debutTime2 >= $finTime2){
                        $(this).attr('disabled', true);
                    } else {
                        $(this).removeAttr('disabled');
                    }
                });
            });

            //Restriction créneau de la troisième ligne
            $('#tableID tbody #addRow2 td span select[name="BX_debut[]"]').on('change', function () {
                $debutTime3 = $(this).val();

                $('#tableID tbody #addRow2 td span select[name="BX_fin[]"] option').each(function () {
                    $finTime3 = $(this).val();
                    if($debutTime3 >= $finTime3){
                        $(this).attr('disabled', true);
                    } else {
                        $(this).removeAttr('disabled');
                    }
                });
            });

            //Restriction créneau de la quatrième ligne
            $('#tableID tbody #addRow3 td span select[name="BX_debut[]"]').on('change', function () {
                $debutTime4 = $(this).val();

                $('#tableID tbody #addRow3 td span select[name="BX_fin[]"] option').each(function () {
                    $finTime4 = $(this).val();

                    if($debutTime4 >= $finTime4){
                        $(this).attr('disabled', true);
                    } else {
                        $(this).removeAttr('disabled');
                    }
                });
            });
        }

        // Fonction de suppression de lignes
        function deleteRow() {
            var table = document.getElementById("tableID");
            var rowCount = table.rows.length;
            for(var i=0; i<rowCount; i++) {
                if(rowCount <= 1) {
                    break;
                } else {
                    table.deleteRow(rowCount-1);
                }
                break;
            }

            var table = document.getElementById("tableID");
            var rowCount = table.rows.length;
            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    if(rowCount <= 1) {
                        alert("Il est impossible de supprimer davantage de lignes");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
            }
        }

        /*jQuery('select[name="BX_debut[]"]').on('change', function () {

            $debutTime = $(this).val();
            alert($debutTime);

            // Fonction empêchant la sélection de créneaux plus tôt que celui de la liste début
            $('select[name="BX_fin[]"] option').each(function () {
                $finTime = $(this).val();
                if($debutTime >= $finTime){
                    $(this).attr('disabled', true);
                } else {
                    $(this).removeAttr('disabled');
                }
            });

        });*/
    </script>
</head>

<body>
<!-- Navbar -->
<nav class="navbar navbar-default" id="navbar">
    <div class="container-fluid" id="headerGestion">
        <a class="navbar-brand" id="navbar-brand" href="index.php">
            <img src="./Logo/logo_chateauform.svg" class="Brand">
        </a>
        <a class="navbar-brand" href="index.php">Client</a>
        <a class="navbar-brand" href="?page=reservation">Réservation</a>
    </div>
</nav>
