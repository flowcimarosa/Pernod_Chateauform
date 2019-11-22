<?php
// Vérifaction et récupération du séminaire sélecionnner
if(isset($_POST['btn-del']))
{
    $id = $_GET['delete_id'];

    $seminairesAVenir = $unController->selectThatWhere("seminaire", "COUNT(*) as total ", "dateSeminaire>=CURDATE() AND idClient='".$id."'");
    
    if ($seminairesAVenir['total']>0){
        header("Location:index.php?page=delete-data&delete_id=".$id."&impossible");
        exit();    
    }
    else{      
        $unController->delete($id);  
       header("Location: index.php?page=delete-data&deleted");
        exit();
    }
}

?>

<!-- Ajout du template head et navbar -->
<?php include 'header.php'; ?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h1 class="main_title">Suppression d'un client</h1>
        </div>
    </div>
</div>

    <!-- Vérification et affichage  -->
	<?php
    if(isset($_GET['deleted']))
    {
        ?>
        <div class="container alert alert-success">
        <strong>Le client a bien été supprimé</strong> 
        </div>
        <?php
    }
    elseif (isset($_GET['impossible'])) {
        ?>
        <div class="container alert alert-danger">
        Il existe un ou plusieurs séminaire(s) à venir pour ce client. Veuillez d'abord les annuler <strong><a href="index.php?page=consultClient&consult_id=<?php print ($_GET['delete_id']);?>">ici</a></strong>.
        </div>
        <?php
    }
    else
    {
        ?>
        <div class=" container alert alert-danger">
        <strong>Voulez-vous vraiment supprimer ce client ? Tous les séminaires organisés par ce client seront également effacés.</strong>
        </div>
        <?php
    }
    ?>


<div class="container" id="addClient">
 	
	 <?php
     // Vérification si le bouton supprimer a été sélectionné
	 if(isset($_GET['delete_id']))
	 {
		 ?>
         <table class='table table-bordered'>
         <tr>
         <th>#</th>
         <th>Client</th>
         <th>Logo</th>
         </tr>
         <?php
         // Affichage des données du client sélectionné
         foreach ($client as $row): 
         
             ?>
             <tr>
             <td><?php print($row['id']); ?></td>
             <td><?php print($row['libelle']); ?></td>
             <td><img src="./Logo/<?php print($row['logo']); ?>" id="deleteLogoClient"/></td>
             </tr>
             <?php
            endforeach;
         
         ?>
         </table>
         <?php
	 }
	 ?>
    <?php
    // Vérification si le bouton supprimer a été sélectionné
    if(isset($_GET['delete_id']))
    {
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
            <button class="btn btn-large btn-primary" type="submit" name="btn-del">
                <i class="glyphicon glyphicon-trash"></i>
                &nbsp; Oui
            </button>
            <a href="index.php" class="btn btn-success" id="return">
                <i class="glyphicon glyphicon-backward"></i>
                &nbsp; Non
            </a>
        </form>
        <?php
    }
    else
    {
        ?>
        <a href="index.php" class="btn btn-success" id="return">
            <i class="glyphicon glyphicon-backward"></i>
            &nbsp; Retour à l'accueil
        </a>
        <?php
    }
    ?>
</div>