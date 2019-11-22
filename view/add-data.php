<?php

// Vérification avant l'ajout d'un client ainsi que la récupération des données du formulaire
if(isset($_POST['btn-save']))
{
    $libelle = $_POST['libelle'];
    $logo = $_POST['logo'];

    $result = $unController->create($libelle,$logo);

    //Vérification si l'ajout a bien été effectué
    if($result = true)
    {

        header("Location: index.php?page=add-data&inserted");
    }
    else
    {
        header("Location: index.php?page=add-data&failure");
    }
}
?>

<!-- Ajout du template head et navbar -->
<?php include 'header.php'; ?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h2 class="main_title">Ajout d'un nouveau client</h2>
        </div>
    </div>
</div>


<!-- Vérification et affichage de la réponse si l'ajout a été effecctué ou non -->
<?php
if(isset($_GET['inserted']))
{?>
    <div class="container">
        <div class="alert alert-info">
            <strong>Le client a été ajouté !</strong>
            <a href="index.php" class="linkHome"> Accueil</a>
        </div>
    </div>
    <?php
}
else if(isset($_GET['failure']))
{
    ?>
    <div class="container">
        <div class="alert alert-warning">
            <strong>Une erreur est survenue pendant l'ajout !</strong>
        </div>
    </div>
    <?php
}
?>

<!-- Formulaire d'ajout d'un client -->
<div class="container" id="addClient">
    <form method='POST' action="/pernod/index.php?page=add-data">
        <table class='table table-bordered'>
            <tr>
                <td>Client</td>
                <td><input type='text' name='libelle' class='form-control' required></td>
            </tr>

            <tr>
                <td>Logo</td>
                <td>
                    <input class="input-1" id="logoClient" type="file" name="logo" accept="image/*">
                </td>
            </tr>
        </table>

        <button type="submit" class="btn btn-primary" name="btn-save" id="saveClient">
            <span class="glyphicon glyphicon-plus"></span>
            Ajouter
        </button>

        <a href="index.php" class="btn btn-success" id="return">
            <i class="glyphicon glyphicon-backward"></i>
            &nbsp; Retour à l'accueil
        </a>
    </form>
</div>