<?php
// Classe permettant d'afficher les pages correspondantes aux salles
class SallesController {
    public function index() {

        /** @noinspection PhpUnusedLocalVariableInspection */
        $salles = Salle::all();
        require_once('views/salles/index.php');
    }

    /** @noinspection PhpInconsistentReturnPointsInspection */
    public function show() {

        if (!isset($_GET['id']))
            return call('pages', 'error');

        /** @noinspection PhpUnusedLocalVariableInspection */
        $salle = Salle::find($_GET['id']);
        require_once('views/salles/show.php');

    }
}
