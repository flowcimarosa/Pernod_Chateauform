<?php

// Classe permettant de récupérer les données des salles de la BDD
class Salle {

    public $idSalle;
    public $libelle;
    public $fonction;
    public $parcoursA1;
    public $parcoursA2;
    public $parcoursA3;
    public $intitule;

    public function __construct($idSalle, $libelle, $fonction, $parcoursA1, $parcoursA2, $parcoursA3, $intitule) {
        $this->idSalle = $idSalle;
        $this->libelle = $libelle;
        $this->fonction = $fonction;
        $this->parcoursA1 = $parcoursA1;
        $this->parcoursA2 = $parcoursA2;
        $this->parcoursA3 = $parcoursA3;
        $this->intitule = $intitule;
    }

    // Fonction qui retourne tous les données de toutes les salles
    public static function all() {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM salle');

        foreach($req->fetchAll() as $salle) {
            $list[] = new Salle($salle['idSalle'], $salle['libelle'], $salle['fonction'], $salle['parcoursA1'], $salle['parcoursA2'], $salle['parcoursA3'], $salle['intitule']);
        }

        return $list;
    }

    // Fonction qui retourne la salle correspondant à son id
    public static function find($id) {
        $db = Db::getInstance();

        $id = intval($id);
        $req = $db->prepare('SELECT * FROM salle WHERE idSalle = :id');

        $req->execute(array('id' => $id));
        $salle = $req->fetch();

        return new Salle($salle['idSalle'], $salle['libelle'], $salle['fonction'], $salle['parcoursA1'], $salle['parcoursA2'], $salle['parcoursA3'], $salle['intitule']);
    }

    public function getIdSalle(){
        return $this->idSalle;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getFonction(){
        return $this->fonction;
    }

    public function getParcoursA1(){
        return $this->parcoursA1;
    }

    public function getParcoursA2(){
        return $this->parcoursA2;
    }

    public function getParcoursA3(){
        return $this->parcoursA3;
    }

    public function getIntitule(){
        return $this->intitule;
    }

}


