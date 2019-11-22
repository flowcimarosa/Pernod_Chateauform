<?php

// Paramètres
setlocale(LC_TIME, 'fr', 'fr_FR');

require ("cnit0.1/bdd.php");


// Inclut le controlleur et définit ses paramètres
require ("controller/controller.class.php");
$unController = new Controller($host, $bdd, $user, $mdp);

session_start();

// Je cherche la page qui est demandé
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = "accueil";
}

// En fonction de $page on récupère la page
switch($page) {

    // Accueil, consultation client
    case "accueil":
        $self = $_SERVER['PHP_SELF'];
        $table = "client";

        $records_per_page = 50;
        $resultatsSelectAll = $unController->selectAllLimit($table, $records_per_page);

        if(isset($_GET["rechercheClient"])){
            $rechercheClient = $_GET['rechercheClient'];
            $where = "libelle LIKE '"."%".$rechercheClient."%'";
            $listeRecherche = $unController->selectAllWhereLimit($table, $where, $records_per_page);

            $total_no_of_records = $unController->paginglink2($table, $where, $records_per_page, $rechercheClient);
        }

        $total_no_of_records2 = $unController->paginglinkAll($table, $records_per_page);
        require ("view/accueil.php");
        break;
    // Consultation du client
    case "consultClient":
        if(isset($_GET['consult_id'])){
            $consult_id = $_GET['consult_id'];
            $client = $unController->selectAllWhere("client", "client.id = '".$consult_id."'");
            $table = "seminaire";
            $where = "seminaire.idClient = '" . $consult_id . "' ORDER BY seminaire.dateSeminaire DESC ";
            // $where = "seminaire.idClient =" . " $consult_id ". "ORDER BY seminaire.dateSeminaire DESC";
            $records_per_page = 50;
            $seminaire = $unController->selectAllWhereLimit($table, $where, $records_per_page);
            $self = $_SERVER['PHP_SELF'];

            $total_no_of_records = $unController->paginglink($table, $where, $records_per_page, $consult_id);
            // var_dump($client);
            // var_dump($seminaire);
        }
        require ("view/consultClient.php");
        break;

    case "add-data":
        require ("view/add-data.php");
        break;

    case "delete-data":
        if(isset($_GET['delete_id'])){
            $delete_id = $_GET['delete_id'];
            $client = $unController->selectAllWhere("client", "client.id = '".$delete_id."'");
        }
        require ("view/delete-data.php");
        break;

    case "edit-data":
        if(isset($_GET['edit_id'])){
            $edit_id = $_GET['edit_id'];
            $client = $unController->selectAllWhere("client", "client.id = '".$edit_id."'");

        }
        require ("view/edit-data.php");
        break;

    case "reservation":
        $order= " ORDER BY dateSeminaire DESC";
        $seminaire = $unController->selectAllOrderLimit("seminaire", $order, 50);
        $self = $_SERVER['PHP_SELF'];
        $records_per_page = 50;
        $table = "seminaire";
        $total_no_of_records = $unController->paginglinkAll($table, $records_per_page);

        if (isset($_POST["rechercheDate"])) {


            if ($_POST['rechercheDate']==""){
                //CAS AUCUN PARAMETRE  -> Même code que sans recherche
                if ($_POST['rechercheDate2']==""){
                    $wher= "seminaire.idSeminaire IS NOT NULL ORDER BY dateSeminaire DESC";
                    $cas=1;
                }
                //CAS SEULEMENT RECHERCHE2
                else{
                    $wher= "seminaire.dateSeminaire <='".$_POST['rechercheDate2']."' ORDER BY dateSeminaire DESC";
                    $cas=2;
                }
            }

            else{
                // CAS SEULEMENT RECHERCHE1
                if ($_POST['rechercheDate2']==""){
                    $wher= "seminaire.dateSeminaire >='".$_POST['rechercheDate']."' ORDER BY dateSeminaire DESC";
                    $cas=3;
                }
                //CAS DEUX PARAMETRES
                else{
                    $wher= "seminaire.dateSeminaire BETWEEN '" .$_POST['rechercheDate']. "' AND '".$_POST['rechercheDate2']. "' ORDER BY dateSeminaire DESC";
                    $cas=4;
                }

            }
            $total_no_of_records = $unController->paginglink($table, $wher, $records_per_page, $_POST['rechercheDate']);

            $seminaire = $unController->selectAllWhereLimit($table, $wher, 50);

        }//Fin de isset($_POST["rechercheDate"])

        elseif (isset($_GET["more"])) {
            if ($_GET['rechercheDate']==""){
                //CAS AUCUN PARAMETRE  -> Même code que sans recherche
                if ($_GET['rechercheDate2']==""){
                    $wher= "seminaire.idSeminaire IS NOT NULL ORDER BY dateSeminaire DESC";
                    $cas=1;
                }
                //CAS SEULEMENT RECHERCHE2
                else{
                    $wher= "seminaire.dateSeminaire <='".$rechercheDate2."' ORDER BY dateSeminaire DESC";
                    $cas=2;
                }
            }

            else{
                // CAS SEULEMENT RECHERCHE1
                if ($_GET['rechercheDate2']==""){
                    $wher= "seminaire.dateSeminaire >='".$rechercheDate."' ORDER BY dateSeminaire DESC";
                    $cas=3;
                }
                //CAS DEUX PARAMETRES
                else{
                    $wher= "seminaire.dateSeminaire BETWEEN '" .$_GET['rechercheDate']. "' AND '".$_GET['rechercheDate2']."' ORDER BY dateSeminaire DESC";
                    $cas=4;
                }

            }
            $total_no_of_records = $unController->paginglink($table, $wher, $records_per_page, 1);

            $seminaire = $unController->selectAllWhereLimit($table, $wher, 50);

        }

        require ("view/reservation.php");
        break;

    case "addSeminaire":
        if(isset($_GET['reserveur_id'])){
            $reserveur_id = $_GET['reserveur_id'];
        }
        $listeClients = $unController->selectAll("client");
        $listeSalles = $unController->selectAll("salle");
        $listeDisponible = $unController->restriction();
        require ("view/addSeminaire.php");
        break;

    case "modifSeminaire":
        if(isset($_GET['modif_id'])){
            $modif_id = $_GET['modif_id'];
            $seminaire = $unController->selectAllWhere("seminaire", "seminaire.idSeminaire = ".$modif_id);
            $listeSalles = $unController->selectAll("salle");
            $listePeriodes = $unController->selectAllPeriodes($modif_id);
        }
        require ("view/modifSeminaire.php");
        break;

    case "modifCreneaux":
        if(isset($_GET['modif_id'])){
            $modif_id = $_GET['modif_id'];
            $seminaire = $unController->selectAllWhere("seminaire", "seminaire.idSeminaire = ".$modif_id);
            $infos = $unController->getID($modif_id);
            // var_dump($infos);
            if (empty($infos[0]['id'])){
                header("Location:index.php?page=reservation");
            }
            else{
                $client_id = $infos[0]['id'];
            }

            $listeDisponible = $unController->restriction();
            if(!empty($seminaire)){
                $dateSeminaire=$seminaire[0]['dateSeminaire'];
                $nomSeminaire=$seminaire[0]['nomSeminaire'];
            }
            else{
                //REDIRECTION
            }

            $listeClients = $unController->selectAll("client");
            $listeSalles = $unController->selectAll("salle");
            $listeDisponible = $unController->restriction();

        }

        if (isset($_GET['dateSeminaire'])){
            $dateSeminaire = $_GET['dateSeminaire'];
        }

        $listeSalles = $unController->selectAll("salle");


        require ("view/modifCreneaux.php");
        break;

    case "cancelSeminaire":
        if(isset($_GET['cancel_id'])){
            $cancel_id = $_GET['cancel_id'];
            $seminaire = $unController->getInfoClient($cancel_id);
        }
        require ("view/cancelSeminaire.php");
        break;

}
?>