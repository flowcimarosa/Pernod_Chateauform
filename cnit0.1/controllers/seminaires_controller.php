<?php

// Classe permettant d'afficher les différentes pages néccessaire à l'affichage des séminaires(Accueil, ParcoursA1, ParcoursA2, ParcoursA3)
class SeminairesController {
    public function accueil() {

        /** @noinspection PhpUnusedLocalVariableInspection */
        $seminaires = Seminaire::seminairesDuJour();

        require_once('views/seminaires/accueil.php');
    }

    /** @noinspection PhpInconsistentReturnPointsInspection */
    public function show() {

        if (!isset($_GET['id'])){
            return call('pages', 'error');
        }

        $seminaireEnCours = Seminaire::findSeminaireEnCours($_GET['id']);
        // var_dump($seminaireEnCours);

        /** @noinspection PhpUnusedLocalVariableInspection */
        $salle = Seminaire::getSalle($_GET['id']);

        if(!(is_null($seminaireEnCours->idSeminaire))){
            $tranches = Seminaire::setTabSeDerouler($seminaireEnCours->idSeminaire);
            // var_dump($seminaireEnCours->idSeminaire);
            // var_dump($tranches);

            /** @noinspection PhpUnusedLocalVariableInspection */
            $aVenir = Seminaire::scheduling($tranches);
        }

        require_once('views/seminaires/show.php');

    }

    public function parcoursA1() {

        /** @noinspection PhpUnusedLocalVariableInspection */
        $seminaires = Seminaire::seminairesDuJour();
        require_once('views/seminaires/parcoursA1.php');

    }

    public function parcoursA2() {

        /** @noinspection PhpUnusedLocalVariableInspection */
        $seminaires = Seminaire::seminairesDuJour();
        require_once('views/seminaires/parcoursA2.php');

    }

    public function parcoursA3() {

        /** @noinspection PhpUnusedLocalVariableInspection */
        $seminaires = Seminaire::seminairesDuJour();
        require_once('views/seminaires/parcoursA3.php');

    }

}
