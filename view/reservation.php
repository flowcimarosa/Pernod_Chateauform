<?php include 'header.php'; ?>

<div class="container" id="consultReservation">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h1 class="main_title">Recherche par date</h1>
        </div>
    </div>

    <div class="row" id="searchReservation">
        <div class="col-lg-12 col-md-12">
            <form class="form-inline" action="index.php?page=reservation" method="POST">
                <div class="form-group">
                <div class="row">
                    <div class="col-md-1 col-lg-1 labelConsultSeminaire">
                        <span class="">Du :</span>
                    </div>

                    <div class="col-md-2 col-lg-2">
                    <div class='input-group'>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>

                        <input type='date' name='rechercheDate' class='form-control input-sm'>
                    </div>
                </div>
                
                <div class="col-md-1 col-lg-1 col-md-offset-2 col-lg-offset-2 labelConsultSeminaire">
                    <span>Au :</span>
                </div>

                <div class="col-lg-2 col-md-2">
                    <div class='input-group'>
                    
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>

                        <input type='date' name='rechercheDate2' class='form-control input-sm'>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-md-offset-2 col-lg-offset-2">
                    <input type="submit" name="Submit" value="Rechercher" class="btn btn-default btn-sm">
                </div>

                </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container" id="containerReservationConsult">
    <div class="btn_add_seminaire">
        <a href="?page=addSeminaire" class="btn btn-large btn-info">
            <i class="glyphicon glyphicon-plus"></i>
            &nbsp; Ajouter une réservation
        </a>
    </div>

    <table class='table table-bordered table-responsive breakWord'>
        <thead>
            <tr>
                <th class="col-sm-0 col-md-0">#</th>
                <th class="col-sm-1 col-md-1 ">Client</th>
                <th class="col-sm-2 col-md-2 ">Logo</th>
                <th class="col-sm-4 col-lg-4">Séminaire</th>
                <th class="col-sm-2 col-lg-2">Date</th>
                <th class="col-sm-3 col-lg-3">Période(s)</th>
                <th class="col-sm-1 col-lg-1">Affichage</th>
                <th colspan="2" align="center" class="col-sm-1 col-lg-1">Actions</th>
            </tr>
        </thead>
        <?php

        //AFFICHAGE SI LA RECHERCHE A ETE LANCEE !!!!!!!!!!!!!!
        //__________________________________________________________________________________________________________________________________________________________________________________________________________________________//

        if (isset($_POST['rechercheDate'])) {
            $rechercheDate = $_POST['rechercheDate'];
        }
        if (isset($_POST['rechercheDate2'])) {
            $rechercheDate2 = $_POST['rechercheDate2'];
        }
        if (isset($_GET['rechercheDate'])) {
            $rechercheDate = $_GET['rechercheDate'];
        }
        if (isset($_GET['rechercheDate2'])) {
            $rechercheDate2 = $_GET['rechercheDate2'];
        }

        foreach ($seminaire as $unSeminaire):
            $result_id = $unSeminaire['idSeminaire'];

            $table = "sederouler, seminaire, salle";
            $champs = "time_format(debut, '%H:%i') AS debut, time_format(fin, '%H:%i') AS fin, salle.libelle AS libelle, salle.fonction AS fonction";
            $where = "seminaire.idSeminaire =".$result_id." AND sederouler.idSeminaire = seminaire.idSeminaire AND sederouler.idSalle = salle.idSalle ORDER BY debut ASC";

            $periode = $unController->selectChampsWhere($table, $champs, $where);

            $client = $unController->getId($result_id);

            foreach ($client as $leClient) {
                $nomClient=$leClient["libelle"];
                $logoClient=$leClient["logo"];
            }

            ?>
            <tr>
                <td><?=$result_id?></td>

                <td><?=$nomClient ;?></td>
                <td><img src="./Logo/<?=$logoClient ;?>" class="logo thumbnail"/></td>

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

                <td class="hover_cell">
                    <a href="?page=modifSeminaire&modif_id=<?=$result_id;?>&Client=<?php print($nomClient) ?>" class="link_client">
                        <span class="right_space">Modifier</span>
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                </td>
                <td class="hover_cell">
                    <a href="?page=cancelSeminaire&cancel_id=<?=$result_id; ?>" class="link_client">
                        <span class="right_space">Annuler</span>
                        <i class="glyphicon glyphicon-remove-circle"></i>
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>
    </table>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="pagination-wrap">
                <?php

                if($total_no_of_records > 0) { ?>

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

                            if (isset($rechercheDate)) {
                                echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$previous."'>Précédent</a></li>";
                            }
                            else{
                                echo "<li><a href='".$self."?page=reservation&page_no=".$previous."'>Précédent</a></li>";
                            }
                        }

                        if ($total_no_of_pages<10){
                            for($i=1;$i<=$total_no_of_pages;$i++)
                            {
                                if($i==$current_page)
                                {
                                    if (!(isset($rechercheDate))) {
                                        echo "<li><a class='nb_page' href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                    }
                                    else{
                                        echo "<li><a class='nb_page' href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                    }
                                }
                                else{
                                    if (!(isset($rechercheDate))) {
                                        echo "<li><a href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                    }
                                    else{
                                        echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                    }
                                }
                            }
                        }
                        else{
                            if ($current_page>=1 && $current_page<=3){
                                for($i=1;$i<=$total_no_of_pages;$i++)
                                {
                                    if (($i>=1 && $i<=$current_page+2) || ($i>= ($total_no_of_pages - 2) && $i<=$total_no_of_pages)){
                                        if($i==$current_page){
                                            if(!(isset($rechercheDate))){
                                                echo "<li><a class='nb_page' href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                            }
                                            else{
                                                echo "<li><a class='nb_page' href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                            }
                                        } //Sinon, si $i n'est pas la page en cours
                                        else{
                                            if(!(isset($rechercheDate))){
                                                echo "<li><a href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                            }
                                            else{
                                                echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                            }
                                        }
                                    }

                                    if ($i == $current_page + 2){
                                        if (!(isset($rechercheDate))) {
                                            echo "<li><a href='".$self."?page=reservation&page_no=".$i."'>...</a></li>";
                                        }
                                        else{
                                            echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>...</a></li>";
                                        }

                                    } //FIN ...

                                }//Fin  FOR
                            } //FIN entre 1 et 3

                            elseif ($current_page>=($total_no_of_pages - 2) && $current_page<=$total_no_of_pages){
                                for($i=1;$i<=$total_no_of_pages;$i++)
                                {
                                    if (($i>=1 && $i<=3) || ($i>= ($current_page - 2) && $i<=$total_no_of_pages)){
                                        if($i==$current_page){
                                            if (!(isset($rechercheDate))) {
                                                echo "<li><a class='nb_page' href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                            }
                                            else{
                                                echo "<li><a class='nb_page' href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                            }
                                        }
                                        //Sinon, si $i n'est pas la page en cours
                                        else{
                                            if (!(isset($rechercheDate))){
                                                echo "<li><a href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                            }
                                            else{
                                                echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                            }
                                        }
                                    }

                                    if ($i == $current_page - 3){
                                        if (!(isset($rechercheDate))){
                                            echo "<li><a href='".$self."?page=reservation&page_no=".$current_page."'>...</a></li>";
                                        }
                                        else{
                                            echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$current_page."'>...</a></li>";
                                        }
                                    }

                                }//Fin  FOR
                            } //FIN elseif entre Total et Total - 2

                            else{
                                for($i=1;$i<=$total_no_of_pages;$i++)
                                {
                                    if (($i>=1 && $i<=3) || ($i>= ($total_no_of_pages - 2) && $i<=($total_no_of_pages) || $i>=($current_page-2) && $i<=($current_page+2) )){
                                        if($i==$current_page){
                                            if (!(isset($rechercheDate))){
                                                echo "<li><a class='nb_page' href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                            }
                                            else{
                                                echo "<li><a class='nb_page' href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                            }
                                        }
                                        else{
                                            if (!(isset($rechercheDate))){
                                                echo "<li><a href='".$self."?page=reservation&page_no=".$i."'>".$i."</a></li>";
                                            }
                                            else{
                                                echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$i."'>".$i."</a></li>";
                                            }
                                        }
                                    }

                                    if ($i == 3 && $current_page!=4 && $current_page!=5 && $current_page!=6 || $i == ($total_no_of_pages-3) && $current_page!= $total_no_of_pages-3 && $current_page!=$total_no_of_pages-4 && $current_page!=$total_no_of_pages-5){
                                        if (!(isset($rechercheDate))){
                                            echo "<li><a href='".$self."?page=reservation&page_no=".$current_page."'>...</a></li>";
                                        }
                                        else{
                                            echo "<li><a href='".$self."?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$current_page."'>...</a></li>";
                                        }
                                    }

                                }//Fin  FOR
                            }

                        }
                        if($current_page!=$total_no_of_pages)
                        {

                            $next=$current_page+1;
                            if (isset($rechercheDate)) {
                                echo "<li><a href='?page=reservation&more&rechercheDate=".$rechercheDate."&rechercheDate2=".$rechercheDate2."&page_no=".$next."' > Suivant</a></li>";
                            }
                            else{
                                echo "<li><a href='?page=reservation&page_no=".$next."' > Suivant</a></li>";
                            }
                        }
                        ?></ul>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
            </body>
            </html>