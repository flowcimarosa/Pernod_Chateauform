

<?php



//En cas de suppression d'un créneau
if (isset($_POST['idSalle']) && isset($_POST['idSeminaire']) && isset($_POST['salle'])&&isset($_POST['debut']) && isset($_POST['fin']) && !(isset($_POST['btn-update']))){

    $result = $unController->delete3($_POST['idSeminaire'], $_POST['idSalle'], $_POST['debutt']);

    $resultDispon = $unController->execute("UPDATE disponibilite SET idSeminaire = NULL, nomSeminaire = NULL, debutSeminaire = NULL, finSeminaire = NULL, libre = '1' WHERE idSeminaire = '".$modif_id."' AND idSalle ='".$idSalle."' AND debut ='".$debutt."' ;");

    header("Location: index.php?page=modifSeminaire&modif_id=".$modif_id);
    exit;
}

//En cas de modification du séminaire
if(isset($_POST['btn-update'])==true && empty($_POST)==false){

    $idSeminaire=$modif_id;
    $nomSeminaire=$_POST['nomSeminaire'];
    $dateSeminaire=$seminaire[0]['dateSeminaire'];
    $dateCompare=$_POST['dateSeminaire'];
    if ($_POST['message'] == " "){$message="NULL";}
    else{$message="'".$_POST['message']."'";}
    $affNom=$_POST['affNom'];

    if ($_POST['dateSeminaire']<date("Y-m-d")){
        header("Location:index.php?page=modifSeminaire&modif_id=".$modif_id."&wrongdate");
        exit();
    }

    if ($dateCompare != $dateSeminaire) {

        foreach ($listePeriodes as $unePeriode) {

            $wantedDate = $unController->verify($dateCompare, $unePeriode['idSalle']);

            foreach ($wantedDate as $wanted) {

                if (!(is_null($wanted['debutSeminaire'])) && !(is_null($wanted['finSeminaire']))) {
                    // var_dump($wanted);
                    // var_dump($unePeriode['debut']);
                    // var_dump($unePeriode['fin']);
                    if (($unePeriode['debut'] >= $wanted['debutSeminaire'] && $unePeriode['debut'] < $wanted['finSeminaire']) || ($unePeriode['fin'] >= $wanted['debutSeminaire'] && $wanted['finSeminaire'] <= $wanted['finSeminaire'])) {
                        header("Location:index.php?page=modifSeminaire&modif_id=".$modif_id."&forbidden");
                        exit();
                    }

                }
            }

            $change = $unController->execute("UPDATE disponibilite SET dateSeminaire ='". $dateCompare ."' WHERE dateSeminaire='".$dateSeminaire."' AND idSeminaire='".$modif_id."';");
        }

        $dateSeminaire = $dateCompare;
    }

    $resultUpdate = $unController->update2($idSeminaire, $nomSeminaire, $dateSeminaire, $affNom, $message);

    if($resultUpdate==true)
    {
        header("Location: index.php?page=modifSeminaire&modif_id=".$modif_id."&inserted");
        exit();

    }
    else
    {
        header("Location: index.php?page=modifSeminaire&modif_id=".$modif_id."&failure");
        exit();
    }
}

include 'header.php';

?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h2 class="main_title">Modification d'un séminaire</h2>
        </div>
    </div>
</div>

<?php
if(isset($_GET['inserted']))
{
    ?>
    <div class="container alert alert-info">
        <strong>La modification a été effectuée !</strong><a href="index.php?page=reservation"> Retour</a>
    </div>
    <?php
}
else if(isset($_GET['inserted2']))
{
    ?>
    <div class="container alert alert-info">
        <strong>Les horaires ont bien été modifiés !</strong><a href="?index.php?page=reservation"> Retour</a>
    </div>
    <?php
}
else if(isset($_GET['failure']))
{
    ?>
    <div class="container alert alert-warning">
        <strong>Une erreur est survenue pendant la modification !</strong>
    </div>
    <?php
}
else if(isset($_GET['wrongdate']))
{
    ?>
    <div class="container">
        <div class="container alert alert-warning">
            <strong>Il est impossible de réserver à une date antérieure.</strong>
        </div>
    </div>
    <?php
}
else if(isset($_GET['forbidden']))
{
    ?>
    <div class="container">
        <div class="container alert alert-warning">
            <strong>Impossible de déplacer le séminaire à cette date. Un ou plusieurs créneaux sont déjà pris.</strong>
        </div>
    </div>
    <?php
}
?>

<div class="container" id="addClient">
    <form method='post'>
        <?php foreach ($seminaire as $row):
        $result_id = $row['idSeminaire'];
        $dateSeminaire = $row['dateSeminaire'];

        $table = "sederouler, seminaire, salle";
        $champs = "sederouler.idSeminaire AS idSeminaire, sederouler.idSalle as idSalle, sederouler.debut AS debutt, sederouler.fin AS finn, time_format(debut, '%H:%i') AS debut, time_format(fin, '%H:%i') AS fin, salle.libelle AS libelle, salle.fonction AS fonction";
        $where = "seminaire.idSeminaire =".$result_id." AND sederouler.idSeminaire = seminaire.idSeminaire AND sederouler.idSalle = salle.idSalle ORDER BY debut ASC";

        $periode = $unController->selectChampsWhere($table, $champs, $where);
        ?>
        <table class='table table-bordered'>

            <tr>
                <td>Intitulé du séminaire :</td>
                <td>
                    <input type='text' name='nomSeminaire' class='form-control' value="<?=$row['nomSeminaire']?>" autocomplete="off" maxlength="110" required>
                </td>
            </tr>
            <tr>
                <td>Date</td>
                <td>
                    <input type='date' name='dateSeminaire' class='form-control' value="<?=$row['dateSeminaire']?>" required>
                </td>
            </tr>
            <tr>
                <td>Affichage</td>
                <td>
                    <select name="affNom" class="form-control">
                        <?php if ($row['affNom']==1){ ?>
                            <option value="<?=$row['affNom']?>" selected>Oui</option>
                            <option value="0">Non</option>
                            <?php
                        } else{ ?>
                            <option value="1">Oui</option>
                            <option value="<?=$row['affNom']?>" selected>Non</option>

                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Message (facultatif) :</td>
                <td><input type='text' name='message' class='form-control' value="<?=$row['message']?>" maxlength="200"></td>
            </tr>
            <tr>
                <td>Créneau(x)</td>
                <td>
                    <table>

                        <?php
                        $nbi = 0;

                        foreach ($periode as $unePeriode):
                            $nbi = $nbi + 1;?>
                            <form method="POST" action="?page=modifSeminaire&modif_id=<?php print ($_GET['modif_id']);?>" id="selectClient">
                                <tr>
                                    <input type="hidden" name="idSalle" value="<?=$unePeriode['idSalle']?>">
                                    <input type="hidden" name="idSeminaire" value="<?=$unePeriode['idSeminaire']?>">
                                    <td>
                                        <!-- <label>Salle</label> -->
                                        <input type='text' name='salle' class='form-control' value="<?=$unePeriode['libelle']?>" readonly >
                                    </td>
                                    <td>
                                        <!-- <label for="BX_debut[]">Début</label> -->
                                        <input type='text' name='debut' class='form-control' value="<?=$unePeriode['debut']?>" readonly>
                                        <input type='hidden' name='debutt' class='form-control' value="<?=$unePeriode['debutt']?>">

                                    </td>
                                    <td>
                                        <!-- <label for="BX_fin[]">Fin</label> -->
                                        <input type='text' name='fin' class='form-control' value="<?=$unePeriode['fin']?>" readonly>

                                        <input type='hidden' name='finn' class='form-control' value="<?=$unePeriode['finn']?>">
                                    </td>

                                    <td>
                                        <!-- <label for="submit"> </label> -->
                                        <div class="input-group-btn" type="submit" name="submit">
                                            <button class="btn btn-default">
                                                <span class="glyphicon glyphicon-remove"></span></i>
                                            </button>
                                        </div>


                                    </td>


                                    </td>
                                </tr>

                            </form>
                            <?php

                        endforeach;?>

                    </table>
                    <?php if ($nbi<4) {?>
                        <a href="?page=modifCreneaux&modif_id=<?=$modif_id?>&dateSeminaire=<?=$dateSeminaire?>&nbi=<?=$nbi?>" class="btn btn-large btn-secondary"><i class="glyphicon glyphicon-calendar"></i> &nbsp; Modifier les horaires</a>
                    <?php } ?>
                </td>
            </tr>
            <?php endforeach;?>
        </table>
        <button type="submit" class="btn btn-primary" name="btn-update">
            <span class="glyphicon glyphicon-edit"></span> Enregistrer
        </button>
        <a href="?page=reservation" class="btn btn-success" id="return">
            <i class="glyphicon glyphicon-backward"></i>
            &nbsp; Retour
        </a>
    </form>
</div>