<?php

// Vérification et suppression du séminaire
if(isset($_POST['btn-cancel']))
{
    $id = $_GET['cancel_id'];
    $unController->delete2($id);
    $unController->execute("UPDATE disponibilite SET idSeminaire = NULL, nomSeminaire = NULL, debutSeminaire = NULL, finSeminaire = NULL, libre = '1' WHERE idSeminaire = '".$id."';");
    header("Location: index.php?page=cancelSeminaire&deleted");
}

?>

<!-- Ajout du template head et navbar -->
<?php include 'header.php'; ?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h2 class="main_title">Suppression d'un séminaire</h2>
        </div>
    </div>
</div>

<div class="container">
    <!-- Vérification et affichage si la suppression du séminaire est bon -->
    <?php
    if(isset($_GET['deleted']))
    {
        ?>
        <div class="container alert alert-success">
            <strong>Le séminaire a bien été annulé</strong>
        </div>
        <?php
    }
    else
    {
        ?>
        <div class="container alert alert-danger">
            <strong>Voulez-vous vraiment annuler ce séminaire ?</strong>
        </div>
        <?php
    }
    ?>
</div>

<div class="container" id="addClient">
    <!-- Vérification si la sélection du bouton supprimer a été effectué
     et affiche les informations correspondant -->
    <?php if(isset($_GET['cancel_id'])) { ?>
        <table class='table table-bordered'>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Logo</th>
                <th>Séminaire</th>
                <th>Date</th>
                <th>Créneaux</th>
                <th>Affichage</th>
            </tr>
            <!-- Affichage du séminaire sélection précédemment -->
            <?php
            foreach ($seminaire as $row):

                $result_id = $row['idSeminaire'];

                $table = "sederouler, seminaire, salle";
                $champs = "time_format(debut, '%H:%i') AS debut, time_format(fin, '%H:%i') AS fin, salle.libelle AS libelle, salle.fonction AS fonction";
                $where = "seminaire.idSeminaire =".$result_id." AND sederouler.idSeminaire = seminaire.idSeminaire AND sederouler.idSalle = salle.idSalle ORDER BY debut ASC";

                $periode = $unController->selectChampsWhere($table, $champs, $where); ?>
                <tr>
                    <td><?php echo ($row['idSeminaire']); ?></td>
                    <td><?php print($row['libelle']); ?></td>
                    <td><img class="logo" src="Logo/<?php print($row['logo']); ?>"/></td>
                    <td><?php print($row['nomSeminaire']); ?></td>
                    <td><?php print($row['dateSeminaire']); ?></td>
                    <td>
                        <table>
                            <?php
                            foreach ($periode as $unePeriode): ?>
                                <tr>
                                    <td>
                                        <?=$unePeriode['debut']." - ".$unePeriode['fin']?> ; Salle <?=$unePeriode["libelle"]?>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                        </table>
                    </td>
                    <td>
                        <?php if ($row['affNom']==1) {echo "Oui";} else {echo "Non";} ;?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php } if(isset($_GET['cancel_id']))
    {
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
            <button class="btn btn-large btn-primary"  name="btn-cancel">
                <i class="glyphicon glyphicon-trash"></i>
                &nbsp; Oui
            </button>
            <a href="?page=reservation" class="btn btn-success" id="return">
                <i class="glyphicon glyphicon-backward"></i>
                &nbsp; Non
            </a>
        </form>
        <?php
    }
    else
    {
        ?>
        <a href="?page=reservation" class="btn btn-success" id="return">
            <i class="glyphicon glyphicon-backward"></i>
            &nbsp; Retour à l'accueil
        </a>
        <?php
    }
    ?>
</div>