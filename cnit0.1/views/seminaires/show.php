<?php

if (is_null($seminaireEnCours->idSeminaire)){
}

else {
// Variables disponibles
    $affNom = $seminaireEnCours->affNom;
    if ($affNom==1) {
        $log = $seminaireEnCours->logo;
    }
    else{
        $log = "Chateauform.jpg";
    }

    $idClient = $seminaireEnCours->idClient;
    $libelle = $seminaireEnCours->libelle; //Nom de la salle
    $nom = $seminaireEnCours->nom;
    $idSeminaire = $seminaireEnCours->idSeminaire;
    $nomSeminaire = $seminaireEnCours->nomSeminaire;
    $dateSeminaire = $seminaireEnCours->dateSeminaire;
}

foreach ($salle as $row){} ?>

<div class="container cadre container-header">
    <div class="row centerHead">
        <div class="col-lg-11 col-md-11" >
            <h1 id="salle"><?php echo $row ?></h1>
        </div>
    </div>
</div>

<div class="container cadre" id="cadre">
    <!-- Affichage si aucun séminaire n'a lieu dans la journée -->
    <?php if(is_null($seminaireEnCours->idSeminaire)){ ?>
    <div class="row">

        <div class="col-md-12 col-lg-12">
            <img id="logoPernod" src="/pernod/Logo/Chateauform.jpg">
        </div>

        <?php } else {?>

        <div class="row rowSalle">
            <div class="col-lg-12 col-md-12 topSeminaire">
                <img id="logo" src="/pernod/Logo/<?php echo $log ?>">
            </div>
        </div>

        <div class="row textSeminaire">
            <div class="bold col-md-12 col-lg-12">
                <?php if ($affNom==1){ echo $nom." - ";} ?> <?php echo $nomSeminaire; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-12 textHeure">
                <?php
                $message = $seminaireEnCours->message;
                if(!(is_null($seminaireEnCours->idSeminaire))){
                    if (!(is_null($message))){
                        ?>
                        <marquee behavior="scroll" direction="left"><?php print($message);  ?></marquee>
                    <?php }
                }
                ?>

                <?php //On affiche le permier crénau qui est définie
                /*if(isset($aVenir[0])){

                    echo $aVenir[0]['debut']." - ".$aVenir[0]['fin'];

                    //Pour chacun des 3 crénaux restants, vérification si cela se déroule dans la même salle
                    //Si oui, on l'affiche
                    for ($i=1; $i<4; $i++){

                        //Boucle d'affichage des crénaux
                        if (isset($aVenir[$i])){

                            if ($aVenir[$i-1]['libelle'] == $aVenir[$i]['libelle']){

                                echo $aVenir[$i]['debut']." - ".$aVenir[$i]['fin'];
                            }
                        }

                        if ($i == 2){
                            if(isset($aVenir[3])){
                                echo " / ";
                            }
                        } else {
                            echo "<br/>";
                        }
                    }
                } else {
                    echo $tranches[0]['debut']." - ".$tranches[0]['fin'];
                }*/
                } ?>
            </div>
        </div>


    </div>
</div>