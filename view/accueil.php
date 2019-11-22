<!-- Ajout du template head et navbar -->
<?php include ("header.php"); ?>

<!-- Corps Page d'accueil -->
<div class="container" id="containerClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h1 class="main_title">Recherche par client</h1>
        </div>
    </div>

    <!-- Champ de recherche client -->
    <div class="row" id="searchClient">
        <div class="col-lg-6 col-md-6">
            <form action="index.php" method="GET" class="form-inline">
                <div class="form-group">
                    <input list="rechercheClient" name="rechercheClient" class="form-control" id="width_search_client">
                    <datalist id="rechercheClient">
                        <?php foreach ($resultatsSelectAll as $unClient): ?>
                            <option data-value="<?=$unClient['id']?>">
                                <?=$unClient['libelle']?>
                            </option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <input type="submit" name="Submit" value="Rechercher" class="btn btn-default">
            </form>
        </div>
    </div>
</div>

<!-- Container d'un tableau des clients -->
<div class="container" id="tableClient">
    <div id="btn_ajoutClient">
        <a href="?page=add-data" class="btn btn-large btn-info">
            <i class="glyphicon glyphicon-plus"></i> &nbsp; Ajouter un client
        </a>
    </div>

    <table class='table table-bordered table-responsive'>
        <thead>
            <tr>
                <th class="col-md-1">#</th>
                <th class="col-md-3">Client</th>
                <th class="col-md-2">Logo</th>
                <th class="col-md-6" id="col_action" colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
        <!-- Liste de clients de la BDD -->
        <?php if (!empty($listeRecherche)) {
        foreach ($listeRecherche as $unClient): ?>
            <tr>
                <td>
                    <?=$unClient['id']?>
                </td>
                <td>
                    <?=$unClient['libelle']?>
                </td>
                <td class="thumbnail">
                    <img class="logo" src="Logo/<?=$unClient['logo']?>" />
                </td>
                <td>
                    <a href="?page=consultClient&consult_id=<?php print($unClient['id']); ?>" class="padding_cell">
                        Consulter <i class="glyphicon glyphicon-book"></i>
                    </a>
                </td>
                <td>
                    <a href="?page=edit-data&edit_id=<?php print($unClient['id']); ?>" class="padding_cell">
                        Modifier <i class="glyphicon glyphicon-edit"></i>
                    </a>
                </td>
                <td>
                    <a href="?page=delete-data&delete_id=<?php print($unClient['id']); ?>" class="padding_cell">
                        Supprimer <i class="glyphicon glyphicon-remove-circle"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- Fin de la liste des clients de la BDD -->
        <tr>
            <!-- Pagination -->
            <td colspan="12" align="center">
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

                                echo "<li><a href='".$self."?rechercheClient=".$rechercheClient."&page_no=".$previous."'>Précédent</a></li>";
                            }
                            for($i=1;$i<=$total_no_of_pages;$i++)
                            {
                                if($i==$current_page)
                                {
                                    echo "<li><a href='".$self."?rechercheClient=".$rechercheClient."&page_no=".$i."'>".$i."</a></li>";
                                }
                                else
                                {
                                    echo "<li><a href='".$self."?rechercheClient=".$rechercheClient."&page_no=".$i."'>".$i."</a></li>";
                                }
                            }
                            if($current_page!=$total_no_of_pages)
                            {
                                $next=$current_page+1;
                                echo "<li><a href='?rechercheClient=".$rechercheClient."&page_no=".$next."' > Suivant</a></li>";
                            }
                            ?>
                        </ul>
                        <?php
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
    }
    else {
    foreach ($resultatsSelectAll as $unClient): ?>
        <tr>
            <td>
                <?=$unClient['id']?>
            </td>

            <td>
                <?=$unClient['libelle']?>
            </td>

            <td>
                <img src="Logo/<?=$unClient['logo']?>" class="logo thumbnail" id="logo"/>
            </td>

            <td class="hover_cell">
                <a href="?page=consultClient&consult_id=<?php print($unClient['id']); ?>" class="link_client">
                    <span class="right_space">Consulter</span>
                    <i class="glyphicon glyphicon-book"></i>
                </a>
            </td>

            <td class="hover_cell">
                <a href="?page=edit-data&edit_id=<?php print($unClient['id']); ?>" class="link_client">
                    <span class="right_space">Modifier</span>
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
            </td>

            <td class="hover_cell">
                <a href="?page=delete-data&delete_id=<?php print($unClient['id']); ?>" class="link_client">
                    <span class="right_space">Supprimer</span>
                    <i class="glyphicon glyphicon-remove-circle"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="pagination-wrap">
                <?php

                if($total_no_of_records2 > 0) { ?>

                    <ul class="pagination">
                        <?php

                        $total_no_of_pages=ceil($total_no_of_records2/$records_per_page);
                        $current_page=1;

                        if(isset($_GET["page_no"]))
                        {
                            $current_page=$_GET["page_no"];
                        }

                        if($current_page!=1)
                        {
                            $previous = $current_page-1;
                            echo "<li><a href='".$self."?page_no=".$previous."'>Précédent</a></li>";
                        }

                        if ($total_no_of_pages<10){
                            for($i=1;$i<=$total_no_of_pages;$i++)
                            {
                                if($i==$current_page)
                                {
                                    echo "<li><a class='nb_page'>".$i."</a></li>";
                                }
                                else
                                {
                                    echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
                                }
                            }
                        }
                        else{
                            if ($current_page>=1 && $current_page<=3){
                                for($i=1;$i<=$total_no_of_pages;$i++)
                                {
                                    if (($i>=1 && $i<=$current_page+2) || ($i>= ($total_no_of_pages - 2) && $i<=$total_no_of_pages)){
                                        if($i==$current_page){
                                            echo "<li><a class='nb_page'>".$i."</a></li>";
                                        }
                                        else{
                                            echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
                                        }
                                    }

                                    if ($i == $current_page + 2){
                                        echo "<li><a href='".$self."?page_no=".$current_page."'>...</a></li>";
                                    } //FIN ...

                                }//Fin  FOR
                            } //FIN entre 1 et 3

                            elseif ($current_page>=($total_no_of_pages - 2) && $current_page<=$total_no_of_pages){
                                for($i=1;$i<=$total_no_of_pages;$i++)
                                {
                                    if (($i>=1 && $i<=3) || ($i>= ($current_page - 2) && $i<=$total_no_of_pages)){
                                        if($i==$current_page){
                                            echo "<li><a class='nb_page'>".$i."</a></li>";
                                        }
                                        else{
                                            echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
                                        }
                                    }

                                    if ($i == $current_page - 3){
                                        echo "<li><a href='".$self."?page_no=".$current_page."'>...</a></li>";
                                    }

                                }//Fin  FOR
                            } //FIN elseif entre Total et Total - 2

                            else{
                                for($i=1;$i<=$total_no_of_pages;$i++)
                                {
                                    if (($i>=1 && $i<=3) || ($i>= ($total_no_of_pages - 2) && $i<=($total_no_of_pages) || $i>=($current_page-2) && $i<=($current_page+2) )){
                                        if($i==$current_page){
                                            echo "<li><a class='nb_page'>".$i."</a></li>";
                                        }
                                        else{
                                            echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
                                        }
                                    }

                                    if ($i == 3 && $current_page!=4 && $current_page!=5 && $current_page!=6 || $i == ($total_no_of_pages-3) && $current_page!= $total_no_of_pages-3 && $current_page!=$total_no_of_pages-4 && $current_page!=$total_no_of_pages-5){
                                        echo "<li><a href='".$self."?page_no=".$current_page."'>...</a></li>";
                                    }

                                }//Fin  FOR
                            }

                        }

                        if($current_page!=$total_no_of_pages)
                        {
                            $next=$current_page+1;
                            echo "<li><a href='".$self."?page_no=".$next."' > Suivant</a></li>";
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        }
        ?>
</div>
</div>
</body>
</html>

