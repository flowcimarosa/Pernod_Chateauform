<div class="container-fluid container-header">
    <div class="row" id="accueil">
        <div class="col-md-2 col-lg-2 background">
            <img src="/pernod/Logo/logo_chateauform.svg" id="logoAccueil">
        </div>
        <div class="col-md-8 col-lg- background" id="tittleAccueil">
            <h1>Toute notre équipe vous souhaite la bienvenue</h1>
        </div>
    </div>
</div>

<div class="container-fluid" id="container_fluid">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="rig grille columns-2">
            <!-- Affichage s'il n'y a aucun séminaire -->
            <?php if(empty($seminaires)){ ?>
            <div class="container cadre" id="cadre">
                <div class="row">

                    <div class="col-md-12 col-lg-12">
                        <img id="logoPernod" src="/pernod/Logo/Chateauform.jpg">
                    </div>
                </div>

                <?php } else {
                    if (!empty($seminaires)) {

                        $i=0;

                        // Affichage des séminaires du jours
                        foreach($seminaires as $seminaire) { ?>
                            <li class="wignet topClient">
                                <div class="row">
                                    <?php if($seminaire->affNom == 1){ ?>
                                        <div class="col-lg-4 col-md-5 col-sm-3">
                                            <img src="../Logo/<?php echo $seminaire->logo;?>" class="logoAccueilParcours1">
                                        </div>
                                        <div id="textSem" class="col-lg-6 col-md-5 col-sm-7">
                                            <h3 class="marginTopTitle"><?php echo $seminaire->nom; ?> - <?php echo $seminaire->nomSeminaire; ?></h3>
                                            <p class="fontSalle"><?php echo 'Salle : ' . $seminaire->libelle; ?></p>

                                            <p class="fontTop">
                                                <?php

                                                if (!(isset(${'seminaire' . $seminaire->idSeminaire}))){
                                                    ${'seminaire' . $seminaire->idSeminaire} = 0;
                                                }

                                                else{
                                                    ${'seminaire' . $seminaire->idSeminaire} = ${'seminaire' . $seminaire->idSeminaire} + 1;
                                                }

                                                $creneaux = Seminaire::getCreneaux($seminaire->idSeminaire);

                                                $creneaux = Seminaire::schedulingCreneaux($creneaux, ${'seminaire' . $seminaire->idSeminaire});

                                                //echo $creneaux['debut']." - ".$creneaux['fin'];


                                                $lower = $creneaux['parcoursA1'];

                                                ?></p>
                                        </div>
                                        <div id="directionParcours" class="col-lg-2">
                                            <img src="../Logo/<?php echo $lower; ?>" class="direction">
                                        </div>
                                        <?php ;} else {?>
                                    <div id="textSem" class="col-lg-10 col-md-6 col-sm-7">
                                        <h1 class="marginTopTitle"><?php echo $seminaire->nomSeminaire; ?></h1>
                                        <p class="fontSalle"><?php echo 'Salle : ' . $seminaire->libelle; ?></p>

                                        <p class="fontTop">
                                            <?php

                                            if (!(isset(${'seminaire' . $seminaire->idSeminaire}))){
                                                ${'seminaire' . $seminaire->idSeminaire} = 0;
                                            }

                                            else{
                                                ${'seminaire' . $seminaire->idSeminaire} = ${'seminaire' . $seminaire->idSeminaire} + 1;
                                            }

                                            $creneaux = Seminaire::getCreneaux($seminaire->idSeminaire);

                                            $creneaux = Seminaire::schedulingCreneaux($creneaux, ${'seminaire' . $seminaire->idSeminaire});

                                            //echo $creneaux['debut']." - ".$creneaux['fin'];


                                            $lower = $creneaux['parcoursA3'];

                                            ?></p>
                                    </div>
                                    <div id="directionParcours" class="col-lg-2">
                                        <img src="../Logo/<?php echo $lower; ?>" class="direction">
                                    </div>
                                </div>
                            </li><?php } ?>
                            <?php $i=$i+1; }; } }?>
        </ul>
    </div>
</div>