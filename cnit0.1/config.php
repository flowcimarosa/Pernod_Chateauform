<?php
// Ajout des variales de la connexion à la BDD
require ("bdd.php");

// Connexion à la BDD
try
{
	$DB_con = new PDO("mysql:host={$host};dbname={$bdd}",$user,$mdp);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$DB_con->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES, 'utf8'");
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

include_once 'class.crud.php';

$crud = new crud($DB_con);

include_once 'models/salle.php';

$salle = new salle($mysqli);

include_once 'models/seminaire.php';

$seminaire = new seminaire($mysqli);
 
?>
