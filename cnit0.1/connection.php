<?php
// Classe permettant de ce connecter à la BDD
  class Db {
    private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
      if (!isset(self::$instance)) {
        $pdo_options=array(
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

        self::$instance = new PDO('mysql:host=localhost;dbname=chateauform', 'chform', 'Videlio02', $pdo_options);
      }
      return self::$instance;
    }
  }

  