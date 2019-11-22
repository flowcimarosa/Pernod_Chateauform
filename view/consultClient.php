<?php
// Ajout du template head et navbar
include 'header.php';

// Vérification si un client a été sélectionné
if (!isset($_GET['consult_id'])){
    echo "Aucun client n'a été selectionné !";
} else {

?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h1 class="main_title">Consultation d'un client</h1>
        </div>
    </div>
</div>

<div class="container consultClient">
    <!-- Affichage du client sélectionner -->
    <?php foreach ($client as $infoClient): ?>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <img id="logoConsultClient" src="./Logo/<?=$infoClient['logo']?>"/>
        </div>
    </div>

    <!-- Bouton pour réserver avec ce client -->
    <div class="row" id="btnReserverClient">
        <div class="col-lg-3 col-md-3 marginLeftSubmit">
            <form method="POST" action="?page=addSeminaire&reserveur_id=<?=$consult_id?>">

                <input type='hidden' name='infoIdClient' value="<?=$infoClient['id'] ?>">

                <input type='hidden' name='infoNomClient' value="<?=$infoClient['libelle'] ?>">

                <div>
                    <input type="submit" name="informationsClient" class="form-control btn btn-large btn-info" value="Réserver pour <?=$infoClient['libelle']?>">
                </div>
            </form>
        </div>
    </div>
</div>


<?php endforeach; ?>

<div class="container consultClient">
     <table class='table table-bordered table-responsive'>
     <thead>
     <tr>
     <th>#</th>
     <th>Séminaire</th>
     <th>Date</th>
     <th>Période(s)</th>
     <th>Affichage</th>
     <th colspan="2" align="center">Actions</th>
     </tr>
     </thead>
         <!-- Affichage des données correspondant aux séminaires du client -->
        <?php

                foreach ($seminaire as $unSeminaire):

                $result_id = $unSeminaire['idSeminaire'];

                $table = "sederouler, seminaire, salle";
                $champs = "time_format(debut, '%H:%i') AS debut, time_format(fin, '%H:%i') AS fin, salle.libelle AS libelle, salle.fonction AS fonction";
                $where = "seminaire.idSeminaire =".$result_id." AND sederouler.idSeminaire = seminaire.idSeminaire AND sederouler.idSalle = salle.idSalle ORDER BY debut ASC";

                $periode = $unController->selectChampsWhere($table, $champs, $where);
        ?>
                <tr>
                <td><?=$result_id?></td>
                <td><?=$unSeminaire['nomSeminaire']?></td>
                <td><?=$unSeminaire['dateSeminaire']?></td>
                <td>
                    <table>

                    <?php
                     foreach ($periode as $unePeriode): ?>                   
                    <tr>
                    <td><?=$unePeriode['debut']." - ".$unePeriode['fin']?> ; Salle <?=$unePeriode["libelle"]?></td>
                    </tr>
                    <?php endforeach;?>
                    </table>
                </td>    
                
            <td><?php if ($unSeminaire['affNom']==1)
            { echo "Oui";}
            else {echo "Non";} ;?></td>

            <td align="center">Modifier
                <a href="?page=modifSeminaire&modif_id=<?php print($result_id); ?>"><i class="glyphicon glyphicon-edit"></i></a>
            </td>
            <td align="center">Annuler
                <a href="?page=cancelSeminaire&cancel_id=<?php print($result_id); ?>"><i class="glyphicon glyphicon-remove-circle"></i></a>
                </td>

            <?php endforeach; ?>
 
</table>

    <!-- Pagination -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="pagination-wrap">
                <?php if($total_no_of_records > 0) { ?>

                    <ul class="pagination">
                    <?php
                    $total_no_of_pages=ceil($total_no_of_records/$records_per_page);
                    $current_page=1;
                    if(isset($_GET["page_no"]))
                    {
                        $current_page=$_GET["page_no"];
                    }
                    if($current_page!=1)
                    {
                        $previous = $current_page-1;

                        echo "<li><a href='".$self."?page=consultClient&consult_id=".$consult_id."&page_no=".$previous."'>Précédent</a></li>";
                    }
                    for($i=1;$i<=$total_no_of_pages;$i++)
                    {
                        if($i==$current_page)
                        {
                            echo "<li><a href='".$self."?page=consultClient&consult_id=".$consult_id."&page_no=".$i."' class='nb_page'>".$i."</a></li>";
                        }
                        else
                        {
                            echo "<li><a href='".$self."?page=consultClient&consult_id=".$consult_id."&page_no=".$i."'>".$i."</a></li>";
                        }
                    }
                    if($current_page!=$total_no_of_pages)
                    {

                        $next=$current_page+1;
                        echo "<li><a href='index.php?page=consultClient&consult_id=".$consult_id."&page_no=".$next."' > Suivant</a></li>";


                    }
                    ?></ul><?php
                }

                ?>
            </div>
        </div>
    </div>

 <?php
}
?>  
       
</div>

</body>
</html>