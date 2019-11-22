<div class="container-fluid container-header">
    <div class="row" id="accueil">
        <div class="col-md-2 col-lg-2 background">
            <img src="/pernod/Logo/logo_chateauform.svg" id="logoAccueil">
        </div>
        <div class="col-md-8 col-lg-8 background" id="tittleAccueil">
            <h1>Toute notre équipe vous souhaite la bienvenue</h1>
        </div>

    </div>
</div>

<div class="container-fluid">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="rig columns-3 grille">
            <!-- Affichage si aucun séminaire n'est prévu -->
            <?php if(empty($seminaires)){ ?>
            <div class="container cadre" id="cadre">
                <div class="row">

                    <div class="col-md-12 col-lg-12">
                        <img id="logoPernod" src="/pernod/Logo/Chateauform.jpg">
                    </div>
                </div>

                <?php } else {
                if (!empty($seminaires)) {

                //Affichage des séminaires du jour
                foreach($seminaires as $seminaire) { ?>

                <li class="wignet topWignet">
                    <?php if($seminaire->affNom == 1){ ?>
                    <div class="row rowLogoAccueil">
                        <div class="col-md-12 col-lg-12">

                            <img src="../Logo/<?php echo $seminaire->logo ;?>" class="logoAccueil thumbnail2">
                        </div>
                    </div>

                    <h4><?php echo $seminaire->nom; ?> - <?php echo $seminaire->nomSeminaire; ?></h4>
                    <?php }
                    else{ ?>

    <h1><?php echo $seminaire->nomSeminaire; ?></h1>
    <?php
    }
    ?>

    <?php

    if (!(isset(${'seminaire' . $seminaire->idSeminaire}))){
        ${'seminaire' . $seminaire->idSeminaire} = 0;
    }

    else{
        ${'seminaire' . $seminaire->idSeminaire} = ${'seminaire' . $seminaire->idSeminaire} + 1;
    }

    $creneaux = Seminaire::getCreneaux($seminaire->idSeminaire ,${'seminaire' . $seminaire->idSeminaire});

    $creneaux = Seminaire::schedulingCreneaux($creneaux, ${'seminaire' . $seminaire->idSeminaire});};
    ?>
            </div></div>
    </li>
    <?php } } ?>
    </ul>
</div>
</div>