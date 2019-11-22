<?php

class Model {

    private $pdo, $table;

    public function __construct ($host, $bdd, $user, $mdp) {

        $this->pdo = null;

        try {
            $this->pdo = new PDO ("mysql:host=".$host.";dbname=".$bdd, $user, $mdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch (Exception $exp) {
            echo "Erreur de connexion à la BDD";
        }

    }


    public function renseigner($table) {
        $this->table = $table;
    }

    public function getLastId($sql) {
        try
        {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $lastId = $this->pdo->lastInsertId();
            return $lastId;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

    public function execute($sql) {
        try
        {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }


    public function verify($dateSeminaire, $idSalle){
        if ($this->pdo != null) {
            $requete = $sql3 = "SELECT creneau, idSeminaire, libre, debutSeminaire, finSeminaire FROM disponibilite 
        WHERE dateSeminaire = '".$dateSeminaire."' 
        AND idSalle ='". $idSalle . "' ORDER BY creneau;";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

    public function restriction(){
        if ($this->pdo != null) {
            $requete = $sql3 = "SELECT * FROM disponibilite 
            WHERE dateSeminaire >= CURDATE() 
            AND libre = 0 
            AND `idSeminaire` IS NOT NULL
            AND nomSeminaire IS NOT NULL
            AND `debutSeminaire` IS NOT NULL
            AND finSeminaire IS NOT NULL
            GROUP BY `debutSeminaire`
            ORDER BY creneau;";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

    public function select($where){

        if ($this->pdo != null) {
            $requete = "SELECT * FROM ".$this->table." WHERE ".$where.";";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetch();
            return $resultats;
        } else {
            return null;
        }
    }

    public function selectThatWhere($that, $where){

        if ($this->pdo != null) {
            $requete = "SELECT ".$that." FROM ".$this->table." WHERE ".$where.";";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

    public function selectAll() {

        if ($this->pdo != null) {
            $requete = "SELECT * FROM ".$this->table.";";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }

    public function selectAllPeriodes($idSeminaire) {

        if ($this->pdo != null) {
            $requete = "SELECT * FROM sederouler WHERE idSeminaire = ". $idSeminaire. ";";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }

    public function selectAllDistinct() {

        if ($this->pdo != null) {
            $requete = "SELECT DISTINCT ville FROM ".$this->table.";";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }


    public function selectAllWhere($where) {

        if ($this->pdo != null) {
            $requete = "SELECT * FROM ".$this->table." WHERE ".$where.";";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }

    public function selectChampsWhere($champs, $where) {

        if ($this->pdo != null) {
            $requete = "SELECT ".$champs." FROM ".$this->table." WHERE ".$where.";";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }


    public function selectAllLimit($records_per_page)
    {
        $limit1=0;
        if(isset($_GET["page_no"]))
        {
            $limit1=($_GET["page_no"]-1)*$records_per_page;
        }

        if ($this->pdo != null) {
            $requete = "SELECT * FROM ".$this->table. " limit $limit1, $records_per_page ;";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

    public function selectAllWhereLimit($where, $records_per_page)
    {
        // $arrayTest = array();
        $limit1=0;
        $limit2=$records_per_page;
        if(isset($_GET["page_no"]))
        {
            $limit1=($_GET["page_no"]-1)*$records_per_page;
        }

        // $arrayTest[] = $_GET["page_no"];
        // $arrayTest[] = $limit1;
        // $arrayTest[] = $limit2;

        if ($this->pdo != null) {
            $requete = "SELECT * FROM ".$this->table." WHERE ".$where." limit $limit1, $records_per_page ;";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;

        } else {
            return null;
        }
    }

    public function selectAllOrderLimit($order, $records_per_page)
    {
        $limit1=0;
        if(isset($_GET["page_no"]))
        {
            $limit1=($_GET["page_no"]-1)*$records_per_page;
        }

        if ($this->pdo != null) {
            $requete = "SELECT * FROM ".$this->table. $order." limit $limit1,$records_per_page";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }


    public function paginglink($where, $records_per_page, $consult_id)
    {
        if ($this->pdo != null) {
            $query = "SELECT * FROM ".$this->table." WHERE ".$where.";";

            $select = $this->pdo->prepare($query);
            $select->execute();
            $total_no_of_records = $select->rowCount();

            return $total_no_of_records;
        }
    }

    public function paginglink2($where, $records_per_page, $rechercheClient)
    {
        if ($this->pdo != null) {
            $query = "SELECT * FROM ".$this->table." WHERE ".$where.";";

            $select = $this->pdo->prepare($query);
            $select->execute();
            $total_no_of_records = $select->rowCount();

            return $total_no_of_records;
        }
    }

    public function paginglinkAll($records_per_page)
    {
        if ($this->pdo != null) {
            $query = "SELECT * FROM ".$this->table.";";

            $select = $this->pdo->prepare($query);
            $select->execute();
            $total_no_of_records = $select->rowCount();

            return $total_no_of_records;
        }
    }


    public function paging($query,$records_per_page)
    {
        $starting_position=0;
        if(isset($_GET["page_no"]))
        {
            $starting_position=($_GET["page_no"]-1)*$records_per_page;
        }
        $query2=$query." limit $starting_position,$records_per_page";
        return $query2;
    }


    public function dataview($query)
    {
        if ($this->pdo != null) {
            $requete = $query;

            $select = $this->pdo->prepare($requete);
            $select->execute();

            if($select->rowCount()>0)
            {
                while($resultats=$select->fetch(PDO::FETCH_ASSOC)){

                    ?>
                    <tr>
                        <td><?=$resultats['idSeminaire']?></td>
                        <td><?=$resultats['nomSeminaire']?></td>
                        <td><?=$resultats['dateSeminaire']?></td>
                        <td><?php
                            if (isset($creneaux)){
                                foreach ($creneaux as $unCreneau) {
                                    echo $unCreneau['creneau'];
                                    echo $unCreneau['lieu'];
                                }

                            }


                            ?></td>

                        <td align="center">Consulter
                            <a href="consult.php?consult_id=<?php print($resultats['id']); ?>"><i class="glyphicon glyphicon-book"></i></a>
                        </td>

                        <td align="center">Modifier
                            <a href="edit-data.php?edit_id=<?php print($resultats['id']); ?>"><i class="glyphicon glyphicon-edit"></i></a>
                        </td>
                        <td align="center">Supprimer
                            <a href="delete.php?delete_id=<?php print($resultats['id']); ?>"><i class="glyphicon glyphicon-remove-circle"></i></a>
                        </td>
                    </tr>
                    <?php
                }
            }

            ?>

            <?php
        } else {
            return null;
        }
    }

    public function dataviewClient($query)
    {
        if ($this->pdo != null) {
            $requete = "SELECT * FROM seminaire, client
                        WHERE seminaire.idClient=client.id                        
            ";

            $select = $this->pdo->prepare($requete);
            $select->execute();

            return $select;

        } else {
            return null;
        }
    } //if PDO NOT NULL





    public function getCreneaux($id){
        if ($this->pdo != null) {
            $requete = "SELECT CONCAT(debut, '-', fin) AS creneau, CONCAT( salle.libelle, '-', salle.fonction) AS lieu FROM salle, sederouler, seminaire
WHERE sederouler.idSalle=salle.idSalle
AND sederouler.idSeminaire=seminaire.idSeminaire
GROUP BY sederouler.idSeminaire
HAVING 
ORDER BY creneau ASC";
            $select = $this->pdo->prepare($requete);
            $select->execute();

            if ($select->rowCount()>0)
            {
                while($creneaux=$select->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <tr>
                        <td><?=$creneaux['creneau']?></td>
                        <td><?=$creneaux['lieu']?></td>

                    </tr>
                    <?php
                }
            }
        }

    }


    public function selectAllCount() {

        if ($this->pdo != null) {
            $requete = "SELECT COUNT(*) AS nb FROM ".$this->table.";";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetch();
            return $resultats;
        } else {
            return null;
        }

    }

    public function selectAllWhereDistinct($tab, $where) {

        if ($this->pdo != null) {
            $requete = "SELECT ".$tab." FROM ".$this->table." WHERE ".$where.";";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }

    public function selectLastOffer($limit) {

        $requete = "SELECT * FROM ".$this->table. " LIMIT 0, " .$limit.";";

        if ($this->pdo != null) {
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultatsLastOffer = $select->fetchAll();
            return $resultatsLastOffer;
        } else {
            return null;
        }

    }

    public function selectChamps($tab) { // $tab = ensemble de champs

        $champs = implode(",", $tab); // Rassemble les éléments d'un tableau en une chaîne (séparé par une virgule)

        if ($this->pdo != null) {
            $requete = "SELECT ".$champs." FROM ".$this->table.";";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }

    public function selectAllWithFK($tab, $where) {
        $requete = "SELECT ".$tab." FROM ".$this->table." WHERE ".$where.";";

        if ($this->pdo != null) {
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultatsWithFK = $select->fetchAll();
            return $resultatsWithFK;
        } else {
            return null;
        }

    }

    public function selectWhere($tab, $where) {

        /*
            Présent dans l'index :
            $tab = array("nom", "prenom", "classe");
            $where = array("prenom"=>"Alexandre", "nom"=>"Da Costa");
        */
        $champs = implode(",", $tab);
        $clause = array();
        $donnees = array();

        // Pour chaque éléments du tableau $where (défini dans l'index) on les affectes à $cle et $valeur
        foreach ($where as $cle=>$valeur) {
            $clause[] = $cle." = :".$cle; // Défini les clauses (conditions where)
            $donnees[":".$cle] = $valeur; // Défini une valeur pour chaque clé
        }


        $chaine = implode (" AND ", $clause); // Rassemble les éléments d'un tableau en une chaîne (séparé par un AND)
        $requete = "SELECT ".$champs." FROM ".$this->table." WHERE ".$chaine.";";

        if ($this->pdo != null) {
            $select = $this->pdo->prepare($requete);
            /*
                $requete = SELECT nom, prenom, classe FROM eleve WHERE prenom = :prenom AND nom = :nom;
            */
            $select->execute($donnees);
            /*
                $donnees = :prenom = Alexandre :nom = Da Costa
            */
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }

    }

    public function insert($values) {

        $champs = array();
        $valeurs = array();
        $donnees = array();

        foreach ($values as $cle=>$valeur) {
            $champs[] = $cle;
            $valeurs[] = ":".$cle;
            $donnees[":".$cle] = $valeur;
        }

        $chaineChamps = implode(",", $champs);
        $chaineValeurs = implode(",", $valeurs);
        $requete = "INSERT INTO ".$this->table." (".$chaineChamps.") VALUES (".$chaineValeurs.");";

        $insert = $this->pdo->prepare($requete);
        $insert->execute($donnees);

    }

    // public function update($tab, $where) {

    //     /*
    //         Présent dans l'index :
    //         $tab = array("nom", "prenom", "classe");
    //         $where = array("prenom"=>"Sirine", "nom"=>"Zine");
    //     */
    //     $clause1 = array();
    //     $clause2 = array();
    //     $donnees = array();

    //     // Pour chaque éléments du tableau $where (défini dans l'index) on les affectes à $cle et $valeur
    //     foreach ($tab as $cle=>$valeur) {
    //         $clause1[] = $cle." = :".$cle; // Défini les clauses (conditions where)
    //         $donnees[":".$cle] = $valeur; // Défini une valeur pour chaque clé
    //     }
    //     foreach ($where as $cle=>$valeur) {
    //         $clause2[] = $cle." = :".$cle; // Défini les clauses (conditions where)
    //         $donnees[":".$cle] = $valeur; // Défini une valeur pour chaque clé
    //     }

    //     $chaine1 = implode (" , ", $clause1); // Rassemble les éléments d'un tableau en une chaîne (séparé par un AND)
    //     $chaine2 = implode (" AND ", $clause2);
    //     $requete = "UPDATE ".$this->table." SET ".$chaine1." WHERE ".$chaine2.";";

    //     if ($this->pdo != null) {
    //         $update = $this->pdo->prepare($requete);
    //         /*
    //             $requete = SELECT nom, prenom, classe FROM eleve WHERE prenom = :prenom AND nom = :nom;
    //         */
    //         $update->execute($donnees);
    //         /*
    //             $donnees = :prenom = Sirine :nom = Zine
    //         */
    //     } else {
    //         return null;
    //     }

    // }

    public function inscription($valuesU, $valuesP) {

        // Insertion dans la table utilisateur

        $table = explode(",", $this->table);

        $champsU = array();
        $valeursU = array();
        $donneesU = array();

        foreach ($valuesU as $cle=>$valeur) {
            $champsU[] = $cle;
            $valeursU[] = ":".$cle;
            $donneesU[":".$cle] = $valeur;
        }

        $chaineChamps = implode(",", $champsU);
        $chaineValeurs = implode(",", $valeursU);

        $requete = "INSERT INTO ".$table[0]."(".$chaineChamps.") VALUES (".$chaineValeurs.");";
        $insert = $this->pdo->prepare($requete);
        $insert->execute($donneesU);

        // Insertion dans la table particulier

        $sql = "SELECT max(idUtilisateur) AS idUtilisateur
                FROM ".$table[0].";
        ";
        $select = $this->pdo->prepare($sql);
        $select->execute();
        $resultat = $select->fetch(PDO::FETCH_ASSOC);

        $champsP = array();
        $valeursP = array();
        $donneesP = array();

        foreach ($valuesP as $cle=>$valeur) {
            $champsP[] = $cle;
            $valeursP[] = ":".$cle;
            $donneesP[":".$cle] = $valeur;
        }
        $donneesP[":idUtilisateur"] = $resultat["idUtilisateur"];
        $chaineChamps = implode(",", $champsP);
        $chaineValeurs = implode(",", $valeursP);

        $requete = "INSERT INTO ".$table[1]."(".$chaineChamps.",idUtilisateur) VALUES (".$chaineValeurs.",:idUtilisateur);
        ";
        $insert = $this->pdo->prepare($requete);
        $insert->execute($donneesP);

        echo '
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="alert alert-success">
                        Inscription réussi !
                    </div>
                </div>
            </div>
            <meta http-equiv="refresh" content="3; URL=login">
        ';

    }

    public function connexion($tab, $where) {

        $table = explode(",", $this->table);
        $champs = implode(",", $tab);
        $clause = array();
        $donnees = array();

        // Pour chaque éléments du tableau $where (défini dans l'index) on les affectes à $cle et $valeur
        foreach ($where as $cle=>$valeur) {
            $clause[] = $cle." = :".$cle; // Défini les clauses (conditions where)
            $donnees[":".$cle] = $valeur; // Défini une valeur pour chaque clé
        }

        if ($this->pdo != null) {
            $chaine = implode (" AND ", $clause); // Rassemble les éléments d'un tableau en une chaîne (séparé par un AND)
            $requete = "SELECT ".$champs." FROM ".$table[0]." WHERE ".$chaine.";";

            $connexion = $this->pdo->prepare($requete);
            $connexion->execute($donnees);
            $user_connected = $connexion->fetch(PDO::FETCH_ASSOC);

            if ($user_connected != false) {
                $_SESSION = $user_connected;
                echo '<meta http-equiv="refresh" content="0; URL=accueil">';
            } else {
                $chaine = implode (" AND ", $clause); // Rassemble les éléments d'un tableau en une chaîne (séparé par un AND)
                $requete = "SELECT ".$champs." FROM ".$table[1]." WHERE ".$chaine.";";

                $connexion = $this->pdo->prepare($requete);
                $connexion->execute($donnees);
                $user_connected = $connexion->fetch(PDO::FETCH_ASSOC);

                if ($user_connected != false) {
                    $_SESSION = $user_connected;
                    echo '<meta http-equiv="refresh" content="0; URL=accueil">';
                } else {
                    echo '  <div class="row">
                                <div class="col-lg-6 col-lg-offset-3">
                                    <div class="alert alert-danger">
                                        Echec de la connexion : email ou mot de passe incorrect.
                                    </div>
                                </div>
                            </div>
                    ';
                }
            }
        } else {
            return null;
        }

    }


    public function create($libelle,$logo)
    {
        try
        {
            $stmt = $this->pdo->prepare("INSERT INTO client(id, libelle, logo) VALUES(NULL, :libelle, :logo)");
            $stmt->bindparam(":libelle",$libelle);
            $stmt->bindparam(":logo",$logo);
            $stmt->execute();
            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }

    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM client WHERE id=:id");
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return true;
    }

    public function delete2($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM seminaire WHERE idSeminaire=:id");
        $stmt->bindparam(":id",$id);
        $stmt->execute();
        return true;
    }

    public function delete3($idSeminaire, $idSalle, $debut)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sederouler WHERE idSeminaire='".$idSeminaire."' AND idSalle='".$idSalle."' AND debut='".$debut."';");
        $stmt->bindparam(":idSeminaire",$idSeminaire);
        $stmt->bindparam(":idSalle",$idSalle);
        $stmt->bindparam(":debut",$debut);
        $stmt->execute();
        return true;
    }

    public function eraseSeminaire($idSeminaire)
    {
        $stmt = $this->pdo->prepare("DELETE FROM seminaire WHERE idSeminaire='".$idSeminaire."';");
        $stmt->bindparam(":idSeminaire",$idSeminaire);
        $stmt->execute();
        return true;
    }

    public function eraseDerouler($idSeminaire)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sederouler WHERE idSeminaire='".$idSeminaire."';");
        $stmt->bindparam(":idSeminaire",$idSeminaire);
        $stmt->execute();
        return true;
    }

    public function eraseDispo($idSeminaire)
    {
        $stmt = $this->pdo->prepare("DELETE FROM disponibilite WHERE idSeminaire='".$idSeminaire."';");
        $stmt->bindparam(":idSeminaire",$idSeminaire);
        $stmt->execute();
        return true;
    }

    public function clean($dateSeminaire, $idSalle){
        $stmt = $this->pdo->prepare("DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='".$idSalle."';");
        $stmt->bindparam(":dateSeminaire",$dateSeminaire);
        $stmt->bindparam(":idSalle",$idSalle);
        $stmt->execute();
        return true;
    }

    //Trouve l'id du client
    public function getID($id)
    {
        if ($this->pdo != null) {
            $requete = "SELECT * FROM client WHERE id = (SELECT idClient FROM seminaire
            WHERE idSeminaire =".$id.");";
            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }


    public function update($id,$libelle,$logo)
    {
        try
        {
            $stmt=$this->pdo->prepare("UPDATE client SET libelle=:libelle, logo=:logo WHERE id=:id");
            $stmt->bindparam(":libelle",$libelle);
            $stmt->bindparam(":logo",$logo);
            $stmt->bindparam(":id",$id);
            $stmt->execute();

            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

    public function update2($id,$nomSeminaire,$dateSeminaire, $affNom, $message)
    {
        try
        {
            $stmt=$this->pdo->prepare("UPDATE seminaire SET nomSeminaire='".$nomSeminaire."', dateSeminaire='".$dateSeminaire."', message=" .$message.", affNom=".$affNom." WHERE idSeminaire=".$id);
            $stmt->execute();

            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

    public function update3($idSeminaire, $dateSeminaire)
    {
        try
        {
            $stmt=$this->pdo->prepare("UPDATE disponibilite SET nomSeminaire='".$nomSeminaire."', dateSeminaire='".$dateSeminaire."', message=" .$message.", affNom=".$affNom." WHERE idSeminaire=".$id);
            $stmt->execute();

            return true;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

//RESTRICTIONS_______________________________________

    public function dateRestrict($dateSeminaire)
    {
        if ($this->pdo != null) {
            $requete = "SELECT seminaire.idSeminaire, dateSeminaire, salle.idSalle, salle.libelle, debut, fin
FROM sederouler
INNER JOIN seminaire
ON seminaire.idSeminaire = sederouler.idSeminaire
INNER JOIN salle
ON sederouler.idSalle = salle.idSalle
WHERE seminaire.dateSeminaire = ". $dateSeminaire. "
            ;";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

    public function restrictDatetime($dateSeminaire, $BX_salle, $BX_debut, $BX_fin)
    {
        if ($this->pdo != null) {
            $requete = "SELECT debut, fin, nomSeminaire, COUNT(*) AS total 
        FROM sederouler
        INNER JOIN seminaire
        ON sederouler.idSeminaire = seminaire.idSeminaire
        INNER JOIN salle
        ON sederouler.idSalle = salle.idSalle 
        WHERE sederouler.idSalle = 3 
        AND sederouler.debut BETWEEN '00:00:00' AND '00:00:00' 
        AND sederouler.fin BETWEEN '00:00:00' AND '00:00:00'
        GROUP BY debut, fin, nomSeminaire;";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

//AFFICHAGE _________________________________________

    public function seminairesDuJour()
    {
        if ($this->pdo != null) {
            $requete = "SELECT * FROM seminaire
            WHERE seminaire.dateSeminaire =  CURDATE()
            ;";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

    public function getInfoClient($idSeminaire)
    {
        if ($this->pdo != null) {
            $requete = "SELECT * FROM client, seminaire
            WHERE seminaire.idSeminaire = $idSeminaire
            AND seminaire.idClient =  client.id
            ;";

            $select = $this->pdo->prepare($requete);
            $select->execute();
            $resultats = $select->fetchAll();
            return $resultats;
        } else {
            return null;
        }
    }

    public function search($array, $key, $value)
    {
        $results = array();
        for ($nb=0; $nb<count($array); $nb++) {
            if ($array[$nb][$key]==$value){
                $results = array_merge($array[$nb]);
            }
        }
        return $results;
    }

}

?>