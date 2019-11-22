<?php
  class PagesController {
    public function home() {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $first_name = 'Jon';
        /** @noinspection PhpUnusedLocalVariableInspection */
        $last_name  = 'Snow';
      require_once('views/pages/home.php');
    }

    public function error() {
      require_once('views/pages/error.php');
    }
  }
