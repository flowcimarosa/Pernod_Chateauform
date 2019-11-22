<?php
class Seminaire {
    public $idClient;
    public $libelle; //Nom de la salle
    public $nom; //Nom du client
    public $logo;
    public $idSeminaire;
    public $nomSeminaire;
    public $dateSeminaire;
    public $affNom;
    public $message;
    public $parcoursA1;
    public $parcoursA2;
    public $parcoursA3;

    private static $tabSeDerouler ;

    public function __construct($idClient, $libelle, $nom, $logo, $idSeminaire, $nomSeminaire, $dateSeminaire, $affNom, $message, $parcoursA1, $parcoursA2, $parcoursA3){
        $this->idClient=$idClient;
        $this->libelle=$libelle;
        $this->nom=$nom;
        $this->logo=$logo;
        $this->idSeminaire=$idSeminaire;
        $this->nomSeminaire=$nomSeminaire;
        $this->dateSeminaire=$dateSeminaire;
        $this->affNom=$affNom;
        $this->message=$message;
        $this->parcoursA1=$parcoursA1;
        $this->parcoursA2=$parcoursA2;
        $this->parcoursA3=$parcoursA3;

        Seminaire::$tabSeDerouler = array();
    }

    public static function getSalle($id){
        $db = Db::getInstance();
        $req = $db->query("SELECT salle.libelle FROM salle WHERE salle.idSalle = " .$id. ";");
        $req->execute();
        $salle = $req->fetch();
        return $salle;
    }

    public static function setTabSeDerouler($idSeminaire) {

        $db = Db::getInstance();
        $req = $db->query("SELECT time_format(debut, '%H:%i') AS debut, time_format(fin, '%H:%i') AS fin, nomSeminaire, salle.idSalle AS idSalle, salle.libelle AS libelle, salle.fonction AS fonction
        FROM sederouler
        INNER JOIN seminaire
        ON sederouler.idSeminaire = seminaire.idSeminaire
        INNER JOIN salle
        ON sederouler.idSalle = salle.idSalle 
        WHERE seminaire.idSeminaire = ".$idSeminaire. "
        AND NOW() < sederouler.fin
        GROUP BY debut, fin, nomSeminaire, libelle, fonction
        ORDER BY debut ;");

        Seminaire::$tabSeDerouler=$req->fetchAll();

        return Seminaire::$tabSeDerouler;
    }


    public static function getCreneaux($idSeminaire)
    {
        //LIMIT ".$limit.", 1
        $db = Db::getInstance();

        $idSeminaire = intval($idSeminaire);
        $req = $db->prepare("SELECT * FROM sederouler
        INNER JOIN seminaire
        ON seminaire.idSeminaire = sederouler.idSeminaire
        INNER JOIN salle
        ON salle.idSalle = sederouler.idSalle
        WHERE sederouler.idSeminaire = ".$idSeminaire."
        AND seminaire.dateSeminaire = CURDATE()
        AND NOW() < sederouler.fin   
        ORDER BY debut
        ;");

        $req->execute();
        $creneaux = $req->fetchAll();

        return $creneaux;
    }

    public static function schedulingCreneaux($creneaux, $seminaireNb) {
        $creneau = array();
        // var_dump($creneaux);
        // var_dump($seminaireNb);

        $creneau['debut'] = $creneaux[$seminaireNb]['debut'];
        $creneau['fin'] = $creneaux[$seminaireNb]['fin'];
        $creneau['libelle'] = $creneaux[$seminaireNb]['libelle'];
        $creneau['parcoursA1'] = $creneaux[$seminaireNb]['parcoursA1'];
        $creneau['parcoursA2'] = $creneaux[$seminaireNb]['parcoursA2'];
        $creneau['parcoursA3'] = $creneaux[$seminaireNb]['parcoursA3'];

        return $creneau;
    }




    //Affichage Accueil (4 écrans)
    public static function seminairesDuJour() {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query("SELECT client.id AS idClient, salle.libelle AS libelle, client.libelle AS nom, client.logo AS logo, seminaire.idSeminaire AS idSeminaire, seminaire.nomSeminaire AS nomSeminaire, seminaire.dateSeminaire AS dateSeminaire, seminaire.affNom AS affNom, seminaire.message AS message, salle.parcoursA1 AS parcoursA1, salle.parcoursA2 AS parcoursA2, salle.parcoursA3 AS parcoursA3 
        FROM seminaire
        INNER JOIN sederouler 
            ON seminaire.idSeminaire = sederouler.idSeminaire 
        INNER JOIN salle
            ON sederouler.idSalle= salle.idSalle
        INNER JOIN client
            ON seminaire.idClient = client.id
        WHERE seminaire.dateSeminaire = CURDATE()
        AND NOW() < sederouler.fin           
        ORDER BY sederouler.debut;");

        foreach($req->fetchAll() as $seminaire) {
            $list[] = new Seminaire($seminaire['idClient'],
                $seminaire['libelle'],
                $seminaire['nom'],
                $seminaire['logo'],
                $seminaire['idSeminaire'],
                $seminaire['nomSeminaire'],
                $seminaire['dateSeminaire'],
                $seminaire['affNom'],
                $seminaire['message'],
                $seminaire['parcoursA1'],
                $seminaire['parcoursA2'],
                $seminaire['parcoursA3']
            );
        }
        return $list;
    }

    // public static function creneauxParSalle($seminaireEnCours, $tranches, $idSalle){

    //  $db = Db::getInstance();

    //   $idSalle = intval($idSalle);
    //   $req = $db->prepare("SELECT * FROM SeDerouler
    //     INNER JOIN Seminaire
    //     ON Seminaire.idSeminaire = SeDerouler.idSeminaire
    //     INNER JOIN Salle
    //     ON Salle.idSalle = SeDerouler.idSalle
    //     WHERE seDerouler.idSalle = ".$idSalle."
    //     AND Seminaire.dateSeminaire = CURDATE()
    //     ORDER BY debut;");

    //   $req->execute();
    //   $creneaux = $req->fetchAll();

    //   var_dump($creneaux);

    //   for ($i=0; $i < count($creneaux) ; $i++) {
    //     if ($creneaux[$i]['nomSeminaire']=$seminaireEnCours->libelle){

    //     }
    //     else
    //   }

    //   $newList=array();
    // }

    public static function findSeminaireEnCours($id) {
        // SELECTIONNE LE PREMIER SEMINAIRE DE LA JOURNEE
        $db = Db::getInstance();

        // Restriction de temps :
        // AND NOW() BETWEEN DATE_SUB(sederouler.debut, INTERVAL 15 MINUTE)

        $id = intval($id);
        $req = $db->prepare("SELECT client.id AS idClient, salle.libelle AS libelle, client.libelle AS nom, client.logo AS logo, seminaire.idSeminaire AS idSeminaire, seminaire.nomSeminaire AS nomSeminaire, seminaire.dateSeminaire AS dateSeminaire, seminaire.affNom AS affNom, seminaire.message AS message, salle.parcoursA1 AS parcoursA1, salle.parcoursA2 AS parcoursA2, salle.parcoursA3 AS parcoursA3
        FROM seminaire
        INNER JOIN sederouler 
            ON seminaire.idSeminaire = sederouler.idSeminaire 
        INNER JOIN salle
            ON sederouler.idSalle= salle.idSalle
        INNER JOIN client
            ON seminaire.idClient = client.id
        WHERE salle.idSalle=".$id."
            AND seminaire.dateSeminaire = CURDATE()           
            AND NOW() < sederouler.fin
        ORDER BY sederouler.debut
        LIMIT 1;");

        $req->execute();
        $seminaireEnCours = $req->fetch();

        return new Seminaire(
            $seminaireEnCours['idClient'],
            $seminaireEnCours['libelle'],
            $seminaireEnCours['nom'],
            $seminaireEnCours['logo'],
            $seminaireEnCours['idSeminaire'],
            $seminaireEnCours['nomSeminaire'],
            $seminaireEnCours['dateSeminaire'],
            $seminaireEnCours['affNom'],
            $seminaireEnCours['message'],
            $seminaireEnCours['parcoursA1'],
            $seminaireEnCours['parcoursA2'],
            $seminaireEnCours['parcoursA3']);
    }

    /** @noinspection PhpInconsistentReturnPointsInspection */
    public static function scheduling($seDerouler) {
        // if (is_null($seDerouler)){
        //   echo "Null";
        // }

        // if (empty(array())){
        //   echo "Cet array est vide";
        // }
        $sum=count($seDerouler);

        date_default_timezone_set("Europe/Paris");

        $curdatetime = date("H:i:s");

        $restant = $sum;

        for($i=0; $i<$sum; $i++){

            $restant--;

            $dbdatetime = $seDerouler[$i]['debut'];
            // echo "Heure de début";
            // var_dump($dbdatetime);
            $enddatetime = $seDerouler[$i]['fin'];
            // echo "Heure de fin";
            // var_dump($enddatetime);

            $diffDb = strtotime($curdatetime) - strtotime($dbdatetime);
            $diffEnd = strtotime($enddatetime) - strtotime($curdatetime);

            $diffDb= $diffDb/60;
            $diffEnd= $diffEnd/60;
            $dureeSeminaire= strtotime($enddatetime) - strtotime($dbdatetime);
            /** @noinspection PhpUnusedLocalVariableInspection */
            $dureeSeminaire= $dureeSeminaire/60;

            /** @noinspection PhpUnusedLocalVariableInspection */
            $interval= date("H:i:s") - $diffDb;
            // echo "Minutes depuis Début :";
            // var_dump($diffDb);
            // echo "Minutes avant Fin :" ;
            // var_dump($diffEnd);
            // echo "Durée Séminaire";
            // var_dump($dureeSeminaire);

// CONDITION DE STOCKAGE :
// Si le séminaire a commence dans 30 minutes ou a déjà commencé
            $aVenir = array();

            if ($diffDb > -30 && $diffEnd >= 0){
                // echo "Tour en cours (i):";
                // var_dump($i);
                // echo "Tours restants:";
                // var_dump($restant);
                // var_dump($seDerouler[$i]['debut']);
                // var_dump($seDerouler[$i]['fin']);

                $h = $restant+1;
                $j=0;


                while ($h>0){

                    $aVenir[$j]['debut'] = $seDerouler[$i]['debut'];
                    $aVenir[$j]['fin'] = $seDerouler[$i]['fin'];
                    $aVenir[$j]['nomSeminaire'] = $seDerouler[$i]['nomSeminaire'];
                    $aVenir[$j]['idSalle'] = $seDerouler[$i]['idSalle'];
                    $aVenir[$j]['libelle'] = $seDerouler[$i]['libelle'];
                    $aVenir[$j]['fonction'] = $seDerouler[$i]['fonction'];

                    // echo "H:";
                    // var_dump($h);
                    // echo "I:";
                    // var_dump($i);
                    // echo "J:";
                    // var_dump($j);
                    $h--;
                    $j++;
                    $i++;
                    // echo "H après décrémentation:";
                    // var_dump($h);
                    // echo "I après incrémentation:";
                    // var_dump($i);
                    // echo "J après incrémentation:";
                    // var_dump($j);
                }
            }
            return $aVenir;
        }
    }

}