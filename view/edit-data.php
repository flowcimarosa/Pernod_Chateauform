<?php



// Vérification et récupération des données du client sélectionné
if(isset($_POST['btn-update']))
{
    $id = $_GET['edit_id'];
    $libelle = $_POST['libelle'];
    $logo = $_POST['logo'];
    $pic = $_POST['pic'];

    if ($pic=="") {
        $result=$unController->update($id,$libelle,$logo);
    }
    else{
        $result=$unController->update($id,$libelle,$pic);
    }

    if($result==true)
    {
        header("Location: index.php?page=edit-data&edit_id=".$id."&inserted");
        exit;
    }
    else
    {
        header("Location: index.php?page=edit-data&edit_id=".$id."&failure");
        exit;
    }
}

if(isset($_GET['edit_id']))
{
    $id = $_GET['edit_id'];
    extract($unController->getID($id));
}

?>
<!-- Ajout du template head et navbar -->
<?php include 'header.php'; ?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h1 class="main_title">Modification d'un client</h1>
        </div>
    </div>
</div>

<!-- Vérification et affichage de la réponse si la modfication a été effecctué ou non -->
<?php
if(isset($_GET['inserted'])){
    ?>
    <div class="container">
        <div class="container alert alert-info">
            <strong>La modification a été effectuée !</strong>
            <a href="index.php" class="linkHome"> Accueil</a>
        </div>
    </div>
    <?php
}
else if(isset($_GET['failure']))
{
    ?>
    <div class="container">
        <div class="container alert alert-warning">
            <strong>Une erreur est survenue pendant la modification !</strong>
        </div>
    </div>
    <?php
}?>

<div class="container" id="addClient">
    <form method='post'>
        <table class='table table-bordered'>
            <!-- Affichage des données du client sélectionné -->
            <?php foreach ($client as $row): ?>

                <tr>
                    <td>Client</td>
                    <td>
                        <input type='text' name='libelle' class='form-control' value="<?=$row['libelle']?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Logo</td>
                    <td>
                        <img src="./Logo/<?php print($row['logo']); ?>" id="logoModifClient"/>
                        <input type='text' name='logo' class='form-control' value="<?=$row['logo']?>" readonly="readonly" required>

                    </td>
                </tr>

                <td>Changer de logo</td>

                <td>
                    <input type="file" name="pic" accept="image/*">
                </td>
            <?php endforeach; ?>
        </table>
        <button type="submit" class="btn btn-primary" name="btn-update">
            <span class="glyphicon glyphicon-edit"></span> Enregistrer
        </button>
        <a href="index.php" class="btn btn-success" id="return">
            <i class="glyphicon glyphicon-backward">

            </i> &nbsp; Retour
        </a>
    </form>
</div>
