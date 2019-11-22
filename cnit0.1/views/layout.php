<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="refresh" content="30"/>
    <!-- Javascript -->
    <script type="text/javascript" src="/pernod/js/jquery-3.2.1.min.map"></script>
    <script type="text/javascript" src="/pernod/js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/pernod/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/pernod/js/bootstrap.js"></script>
    <script type="text/javascript" src="/pernod/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/pernod/js/Js2.js"></script>
    <script>
        if('serviceWorker' in navigator) {
            navigator.serviceWorker
                .register('/sw.js')
                .then(function() { console.log("Service Worker Registered"); });
        }
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.min.css">
    <link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="/pernod/Css/bootstrap.min.css.map">
    <link rel="stylesheet" href="/pernod/Css/bootstrap-theme.min.css.map">
    <link rel="stylesheet" href="/pernod/Css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/pernod/Css/bootstrap.min.css">
    <link rel="stylesheet" href="/pernod/Css/style.css">
    <link rel="stylesheet" href="/pernod/Css/stylegrid.css" />

    <!-- Favicon -->
    <link rel="icon" href="/pernod/Logo/logo_chateauform.ico">
</head>

<body>
  
    <header hidden>
      <a href='/pernod/cnit0.1'>Home</a>
      <!-- INCLURE UNE NAVBAR -->
    </header>

    <?php require_once('routes.php'); ?>