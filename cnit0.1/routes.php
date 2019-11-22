<?php
  function call($controller, $action) {
    require_once('controllers/' . $controller . '_controller.php');

    switch($controller) {
      case 'pages':
        $controller = new PagesController();
      break;
      case 'salles':
        require_once('models/salle.php');
        $controller = new SallesController();
      break;
      case 'seminaires':
        require_once('models/seminaire.php');
        $controller = new SeminairesController();
      break;

    }

    $controller->{ $action }();
  }

  $controllers = array('pages' => ['home', 'error'],
                       'salles' => ['index', 'show'],
                       'seminaires' => ['accueil', 'parcoursA1', 'parcoursA2', 'parcoursA3', 'show']);


  if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
      call($controller, $action);
    } else {
      call('pages', 'error');
    }
  } else {
    call('pages', 'error');
  }
