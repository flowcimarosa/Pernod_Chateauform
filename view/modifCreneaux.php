<?php

if(isset($_GET['nbi'])){
    $nbi = $_GET['nbi'];
}

if(isset($_GET['modif_id']))
{
    $modif_id = $_GET['modif_id'];
}

if(isset($_GET['reserveur_id']))
{
    $reserveur_id = $_GET['reserveur_id'];
} else {
    $reserveur_id = NULL;
}
$reserveur_nom = "";
?>

<!-- Récupération des données formulaire-->
<?php if(isset($_POST)==true && empty($_POST)==false){

    $BX_sal=$_POST['BX_sal'];
    $BX_debut=$_POST['BX_debut'];
    $BX_fin=$_POST['BX_fin'];

    $countRow = count($BX_sal);

    $dataToInsert = array();
    $loopNb = array();

    for ($i=0; $i<$countRow; $i++) {
        $loopNb[] = $i;
    }
    foreach ($loopNb as $nb) {
        ${'salle' . $nb} = $BX_sal[$nb];
    }
    foreach ($loopNb as $nb) {
        ${'debut' . $nb} = $BX_debut[$nb];
    }
    foreach ($loopNb as $nb) {
        ${'fin' . $nb} = $BX_fin[$nb];
    }

    for ($i=0; $i<$countRow; $i++){
        $dataToInsert[$i] = new StdClass;
        $dataToInsert[$i]->salle = ${'salle' . $i};
        $dataToInsert[$i]->debut = ${'debut' . $i};
        $dataToInsert[$i]->fin = ${'fin' . $i};

        ${'sqll' . $i} = "DELETE FROM `disponibilite` WHERE `dateSeminaire`='". $dateSeminaire."' AND `idSalle`=". $dataToInsert[$i]->salle . ";";
    }

    for ($chev=0; $chev<4; $chev++){
        for ($chev2=0; $chev2<4; $chev2++){
            if ( (isset($dataToInsert[$chev])) && (isset($dataToInsert[$chev2])) && ($chev != $chev2)
                && ($dataToInsert[$chev]->salle == $dataToInsert[$chev2]->salle
                    || ($dataToInsert[$chev]->salle == 9 && ($dataToInsert[$chev2]->salle == 10 || $dataToInsert[$chev2]->salle == 11) )
                    || ($dataToInsert[$chev]->salle == 11 && ($dataToInsert[$chev2]->salle == 10 || $dataToInsert[$chev2]->salle == 9) )
                    || ($dataToInsert[$chev]->salle == 10 && ($dataToInsert[$chev2]->salle == 11 || $dataToInsert[$chev2]->salle == 9) )
                )
            )  {
                if (($dataToInsert[$chev]->debut >= $dataToInsert[$chev2]->debut && $dataToInsert[$chev]->debut < $dataToInsert[$chev2]->fin) || ($dataToInsert[$chev]->fin >= $dataToInsert[$chev2]->debut && $dataToInsert[$chev]->fin <= $dataToInsert[$chev2]->fin) ) {
                    header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbidden");
                    exit();


                }
            }
        }
    }

    $value = "";

    foreach ($loopNb as $nb){
        $value = $value . "(" . "'" .$modif_id . "'" . ", " . "'" . $dataToInsert[$nb]->salle . "'" . ", " . "'" . $dataToInsert[$nb]->debut . "'" . ", " . "'" . $dataToInsert[$nb]->fin . "'" . ") , ";

        if (!(isset(${'destroy' . $nb}))){
            ${'verified' . $nb} = array();

            if ($dataToInsert[$nb]->salle != 9){
                ${'resultt' . $nb} = $unController->verify($dateSeminaire, $dataToInsert[$nb]->salle);
            }
            else{
                ${'resultt' . $nb} = $unController->verify($dateSeminaire, 10);
                $moduVerif = $unController->verify($dateSeminaire, 11);

                if (!(empty($moduVerif))){
                    foreach (${'resultt' . $nb} as $rez) {
                        foreach ($moduVerif as $mod) {
                            if ($mod['libre']==0 && $rez['creneau'] == $mod['creneau']){
                                $rez['creneau']=$mod['creneau'];
                                $rez['idSeminaire']=$mod['idSeminaire'];
                                $rez['libre']=$mod['libre'];
                                $rez['debutSeminaire']=$mod['debutSeminaire'];
                                $rez['finSeminaire']=$mod['finSeminaire'];
                                $rez['libre']=0;
                            }
                        }

                    } //Fin foreach $resultt' . $nb

                } // FIN if !empty $moduVerif
            } //fin else

            ${'resulttSalle' . $nb} = $dataToInsert[$nb]->salle;
        } //fin if isset destroy nb

        if (isset($resultt1) ) {


            if ($resulttSalle0==$resulttSalle1){
                foreach ($resultt1 as $result) {
                    $index=count($resultt0);
                    if (isset($result['creneau']) && $result['idSeminaire'] && $result['libre'] && $result['debut'] && $result['fin']){
                        $resultt0[$index]['creneau']=$result['creneau'];
                        $resultt0[$index]['idSeminaire']=$result['idSeminaire'];
                        $resultt0[$index]['libre']=$result['libre'];
                        $resultt0[$index]['debut']=$result['debut'];
                        $resultt0[$index]['fin']=$result['fin'];
                    }
                    $index++;
                }
                unset($resultt1);
                unset($verified1);
                $destroy1=1;
            }

        }

        if (isset($resultt2)) {
            if ($resulttSalle0==$resulttSalle2){
                foreach ($resultt2 as $result) {
                    $index=count($resultt0);
                    if (isset($result['creneau']) && $result['idSeminaire'] && $result['libre'] && $result['debut'] && $result['fin']){
                        $resultt0[$index]['creneau']=$result['creneau'];
                        $resultt0[$index]['idSeminaire']=$result['idSeminaire'];
                        $resultt0[$index]['libre']=$result['libre'];
                        $resultt0[$index]['debut']=$result['debut'];
                        $resultt0[$index]['fin']=$result['fin'];
                    }

                    $index++;
                }
                unset($resultt2);
                unset($verified2);
                $destroy2=1;
            }
        }

        if (isset($resultt3)) {
            if ($resulttSalle0==$resulttSalle3){
                foreach ($resultt3 as $result) {
                    $index=count($resultt0);
                    if (isset($result['creneau']) && $result['idSeminaire'] && $result['libre'] && $result['debut'] && $result['fin']){
                        $resultt0[$index]['creneau']=$result['creneau'];
                        $resultt0[$index]['idSeminaire']=$result['idSeminaire'];
                        $resultt0[$index]['libre']=$result['libre'];
                        $resultt0[$index]['debut']=$result['debut'];
                        $resultt0[$index]['fin']=$result['fin'];
                    }

                    $index++;
                }
                unset($resultt3);
                unset($verified3);
                $destroy3=0;
            }
        }

        if (isset($resultt1) && isset($resultt2)) {
            if ($resulttSalle1==$resulttSalle2){
                foreach ($resultt2 as $result) {
                    $index=count($resultt1);
                    if (isset($result['creneau']) && $result['idSeminaire'] && $result['libre'] && $result['debut'] && $result['fin']){
                        $resultt1[$index]['creneau']=$result['creneau'];
                        $resultt1[$index]['idSeminaire']=$result['idSeminaire'];
                        $resultt1[$index]['libre']=$result['libre'];
                        $resultt1[$index]['debut']=$result['debut'];
                        $resultt1[$index]['fin']=$result['fin'];
                    }
                    $index++;
                }
                unset($resultt2);
                unset($verified2);
                $destroy2=1;
            }
        }

        if (isset($resultt1) && isset($resultt3)) {
            if ($resulttSalle1==$resulttSalle3){
                foreach ($resultt3 as $result) {
                    $index=count($resultt1);
                    if (isset($result['creneau']) && $result['idSeminaire'] && $result['libre'] && $result['debut'] && $result['fin']){
                        $resultt1[$index]['creneau']=$result['creneau'];
                        $resultt1[$index]['idSeminaire']=$result['idSeminaire'];
                        $resultt1[$index]['libre']=$result['libre'];
                        $resultt1[$index]['debut']=$result['debut'];
                        $resultt1[$index]['fin']=$result['fin'];
                    }

                    $index++;
                }
                unset($resultt3);
                unset($verified3);
                $destroy3=1;
            }
        }

        if (isset($resultt2) && isset($resultt3)) {
            if ($resulttSalle2==$resulttSalle3){
                foreach ($resultt3 as $result) {
                    $index=count($resultt1);
                    if (isset($result['creneau']) && $result['idSeminaire'] && $result['libre'] && $result['debut'] && $result['fin']){
                        $resultt2[$index]['creneau']=$result['creneau'];
                        $resultt2[$index]['idSeminaire']=$result['idSeminaire'];
                        $resultt2[$index]['libre']=$result['libre'];
                        $resultt2[$index]['debut']=$result['debut'];
                        $resultt2[$index]['fin']=$result['fin'];
                    }

                    $index++;
                }
                unset($resultt3);
                unset($verified3);
                $destroy3=2;
            }
        }

        $time = strtotime($dataToInsert[$nb]->fin) - strtotime($dataToInsert[$nb]->debut);
        $time = $time/1800;

        for ($i=0; $i<$time; $i++){
            $creneauRef = '00:00:00';

            for ($j=0; $j<48; $j++) {


                //INITIALISATION DES CRENEAUX DANS CHAQUE ARRAY
                $verified0[$j]['creneau']=$creneauRef;
                if (isset($verified1)) {
                    $verified1[$j]['creneau']=$creneauRef;
                }
                if (isset($verified2)) {
                    $verified2[$j]['creneau']=$creneauRef;
                }
                if (isset($verified3)) {
                    $verified3[$j]['creneau']=$creneauRef;
                }

                if (isset($verified0[$i])){

                    if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                        $verified0[$j]['seminaireId']=$modif_id;
                        $verified0[$j]['debut']=$dataToInsert[$nb]->debut;
                        $verified0[$j]['fin']=$dataToInsert[$nb]->fin;
                        $verified0[$j]['libre']=0;
                    }  //FIN id creneauRef entre debut et fin seminaire.

                    $verified0[$j]['salle']=$resulttSalle0;

                }//FIN if (isset($resultt0[$i]))

                if (isset($verified1[$i])){

                    if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                        $verified1[$j]['seminaireId']=$modif_id;
                        $verified1[$j]['debut']=$dataToInsert[$nb]->debut;
                        $verified1[$j]['fin']=$dataToInsert[$nb]->fin;
                        $verified1[$j]['libre']=0;
                    } //FIN if

                    if (isset($resultt1[0]['salle'])) {
                        if (!(isset($verified1[$j]['salle']))) {
                            $verified1[$j]['salle']=$resultt1[0]['salle'];
                        }
                    }

                    if (isset($destroy1)) {
                        $verified1[$j]['salle']=$resulttSalle0;
                    }
                    else{
                        $verified1[$j]['salle']=$resulttSalle1;
                    }

                } //FIN if (isset($resultt1[$i]))

                if (isset($verified2[$i])){

                    if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                        $verified2[$j]['seminaireId']=$modif_id;
                        $verified2[$j]['debut']=$dataToInsert[$nb]->debut;
                        $verified2[$j]['fin']=$dataToInsert[$nb]->fin;
                        $verified2[$j]['libre']=0;
                    } // FIN if

                    if (isset($destroy2)) {
                        if ($destroy2==0) {
                            $verified2[$j]['salle']=$resulttSalle0;
                        }
                        else if ($destroy2=1) {
                            $verified2[$j]['salle']=$resulttSalle1;
                        }
                    }
                    else{
                        $verified2[$j]['salle']=$resulttSalle2;
                    }

                } //FIN if (isset($resultt2[$i]))

                if (isset($verified3[$i])){

                    if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                        $verified3[$j]['seminaireId']=$modif_id;
                        $verified3[$j]['debut']=$dataToInsert[$nb]->debut;
                        $verified3[$j]['fin']=$dataToInsert[$nb]->fin;
                        $verified3[$j]['libre']=0;
                    } // FIN if

                    if (isset($destroy3)) {
                        if ($destroy3==0) {
                            $verified3[$j]['salle']=$resulttSalle0;
                        }
                        else if ($destroy3=1) {
                            $verified3[$j]['salle']=$resulttSalle1;
                        }
                        else if ($destroy3=2) {
                            $verified3[$j]['salle']=$resulttSalle2;
                        }
                    }
                    else{
                        $verified3[$j]['salle']=$resulttSalle3;
                    }

                } //FIN if (isset($resultt3[$i]))


                $creneauRef = strtotime($creneauRef);
                $creneauRef = date("H:i:s", strtotime('+30 minutes', $creneauRef));
            } // FIN for J<48
        } // FIN FOR I<TIME


        for ($v=0; $v<48; $v++) {

            if (!(isset($verified0[$v]['libre']))){
                $verified0[$v]['libre']=1;
            } //FIN if
            if (isset ($verified1)) {
                if (!(isset($verified1[$v]['libre']))){
                    $verified1[$v]['libre']=1;
                } //FIN if
            }
            if (isset ($verified2)) {
                if (!(isset($verified2[$v]['libre']))){
                    $verified2[$v]['libre']=1;
                } //FIN if
            }
            if (isset ($verified3)) {
                if (!(isset($verified3[$v]['libre']))){
                    $verified3[$v]['libre']=1;
                } //FIN if
            }

        } //FIN FOR V<48
    } //FIN FOREACH LOOPNB

    //ON AFFECTE LES VALEURS DU SEMINAIRE AJOUTE A L'ARRAY $VERIFIED
    foreach ($loopNb as $nb) {
        $creneauRef = '00:00:00';
        for ($j=0; $j<48; $j++) {

            if (isset($resultt0) && !empty($resultt0)){


                $getInfoCreneau = $unController->search($resultt0, "creneau", $creneauRef);

//Contrainte problématique : && $getInfoCreneau['idSeminaire']!=$modif_id


//Si le créneau est occupé &&
//il y a un idSeminaire enregistré &&

//le début de la tranche horaire qu'on souhaite insérer est entre le début et la fin du créneau en cours de vérification déjà enregistré (getInfoCreneau $resultt0) OU BIEN
// la fin (idem)

                if (
                    $getInfoCreneau['libre']==0 &&
                    ( ($dataToInsert[$nb]->debut>=$getInfoCreneau['debutSeminaire'] && $dataToInsert[$nb]->debut<$getInfoCreneau['finSeminaire'])
                        ||
                        ($dataToInsert[$nb]->fin>= $getInfoCreneau['debutSeminaire'] && $dataToInsert[$nb]->fin <= $getInfoCreneau['finSeminaire']) )
                ){

                    header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbiddened");
                    exit();
                    echo "forbiddened";

                }

                else{

                    if ( !(is_null($resultt0[$j]['idSeminaire'])) ) {

                        $verified0[$j]['seminaireId']=$resultt0[$j]['idSeminaire'];
                        $verified0[$j]['debut']=$resultt0[$j]['debutSeminaire'];
                        $verified0[$j]['fin']=$resultt0[$j]['finSeminaire'];
                        $verified0[$j]['libre']=0;
                    }
                }

            }//FIN if isset resultt0

            if (isset($resultt1) && !empty($resultt1)){

                $getInfoCreneau = $unController->search($resultt1, "creneau", $creneauRef);

                if (!(empty($getInfoCreneau))){

                    if ($getInfoCreneau['libre']==0 && !(is_null($getInfoCreneau['idSeminaire']))) {

                        header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbiddened");
                        exit();

                    }
                }

                else{
                    if (isset($resultt1[$j]) && $resultt1[$j]['libre']==1 && !(is_null($resultt1[$j]['idSeminaire'])) && $resultt1[$j]['idSeminaire']!=$modif_id ) {

                        $verified1[$j]['seminaireId']=$resultt1[$j]['idSeminaire'];
                        $verified1[$j]['debut']=$resultt1[$j]['debutSeminaire'];
                        $verified1[$j]['fin']=$resultt1[$j]['finSeminaire'];
                        $verified1[$j]['libre']=0;
                    }
                }
            }

            //FIN if isset resultt1

            if (isset($resultt2) && !empty($resultt2)){

                $getInfoCreneau = $unController->search($resultt2, "creneau", $creneauRef);

                if (!(empty($getInfoCreneau))){

                    if ($getInfoCreneau['libre']==0 && !(is_null($getInfoCreneau['idSeminaire']))) {
                        header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbiddened");
                        exit();

                    }
                }

                else{
                    if (isset($resultt2[$j]) && $resultt2[$j]['libre']==1 && !(is_null($resultt2[$j]['idSeminaire'])) && $resultt2[$j]['idSeminaire']!=$modif_id ) {

                        $verified2[$j]['seminaireId']=$resultt2[$j]['idSeminaire'];
                        $verified2[$j]['debut']=$resultt2[$j]['debutSeminaire'];
                        $verified2[$j]['fin']=$resultt2[$j]['finSeminaire'];
                        $verified2[$j]['libre']=0;
                    }
                }
            }

            //FIN if isset resultt2

            if (isset($resultt3) && !empty($resultt3)){

                $getInfoCreneau = $unController->search($resultt3, "creneau", $creneauRef);

                if (!(empty($getInfoCreneau))){

                    if ($getInfoCreneau['libre']==0 && !(is_null($getInfoCreneau['idSeminaire']))) {
                        header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbiddened");
                        exit();

                    }
                }
                else{
                    if (isset($resultt3[$j]) && $resultt3[$j]['libre']==1 && !(is_null($resultt3[$j]['idSeminaire'])) && $resultt3[$j]['idSeminaire']!=$modif_id ) {

                        $verified3[$j]['seminaireId']=$resultt3[$j]['idSeminaire'];
                        $verified3[$j]['debut']=$resultt3[$j]['debutSeminaire'];
                        $verified3[$j]['fin']=$resultt3[$j]['finSeminaire'];
                        $verified3[$j]['libre']=0;
                    }
                }
            }

            //FIN if isset resultt3

            $creneauRef = strtotime($creneauRef);
            $creneauRef = date("H:i:s", strtotime('+30 minutes', $creneauRef));
        }//FIN DU FOR J<48


    } // FIN DU DEUXIEME FOREACH LOOPNB

    $value3 = "";
    if ($resulttSalle0==9){
        $value3bis = "";
    }

    foreach ($verified0 as $verif) {
        if (isset($verif['fin']) && $verif['debut'] && $verif['fin'] <= $verif['debut']){
            header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&impossible");
            exit();

        }

        if ($verif['libre']==0){
            if($resulttSalle0!=9){
                $value3 = $value3 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . $dataToInsert[0]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
            }
            else{
                $value3 = $value3 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                $value3bis = $value3bis . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
            }
        } // FIN if
        else
        {
            if ($resulttSalle0!=9){
                $value3 = $value3 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . $dataToInsert[0]->salle. "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
            }
            else{
                $value3 = $value3 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "10". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                $value3bis = $value3bis . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "11". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
            }
        } // FIN else


    } // FIN du foreach ($verified0 as $verif)

    $value3 = substr($value3, 0, -2);
    if (isset($value3bis)) {
        $value3bis = substr($value3bis, 0, -2);
    }
//___________________________________________
    if (isset($verified1)) {

        $value4 = "";
        if ($resulttSalle1==9){
            $value4bis = "";
        }

        foreach ($verified1 as $verif) {
            if (isset($verified0)){
                foreach ($verified0 as $verifPrecedente) {
                    if ($verif['salle'] == $verifPrecedente['salle'] ) {
                        if((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))){
                            if ( ($verif['debut']>=$verifPrecedente['debut'] && $verif['debut']<$verifPrecedente['fin']) || ($verif['fin']>= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])){
                                header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbidden");
                                exit();

                            }
                        }

                    }
                }
            }

            if ($verif['libre']==0){
                if($resulttSalle1!=9){
                    $value4 = $value4 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . $dataToInsert[1]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                }
                else{
                    $value4 = $value4 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                    $value4bis = $value4bis . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
                }
            } // FIN if
            else
            {
                if ($resulttSalle1!=9){
                    $value4 = $value4 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . $dataToInsert[1]->salle. "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                }
                else{
                    $value4 = $value4 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "10". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                    $value4bis = $value4bis . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "11". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                }

            } // FIN else

        } // FIN du foreach ($verified1 as $verif)

        $value4 = substr($value4, 0, -2);
        if (isset($value4bis)) {
            $value4bis = substr($value4bis, 0, -2);
        }
    }
//___________________________________________

    if (isset($verified2)) {

        $value5 = "";
        if ($resulttSalle2==9){
            $value5bis = "";
        }

        foreach ($verified2 as $verif) {
            if (isset($verified1)) {
                foreach ($verified1 as $verifPrecedente) {
                    if ($verif['salle'] == $verifPrecedente['salle'] ) {
                        if((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))){
                            if ( ($verif['debut']>=$verifPrecedente['debut'] && $verif['debut']<$verifPrecedente['fin']) || ($verif['fin']>= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])){
                                header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbidden");
                                exit();

                            }
                        }

                    }
                }
            }

            if (isset($verified0)){
                foreach ($verified0 as $verifPrecedente) {
                    if ($verif['salle'] == $verifPrecedente['salle'] ) {
                        if (($verif['debut']>=$verifPrecedente['debut'] && $verif['debut']<$verifPrecedente['fin']) || ($verif['fin']>= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])){
                            header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbidden");
                            exit();

                        }

                    }
                }
            }


            if ($verif['libre']==0){
                if($resulttSalle2!=9){
                    $value5 = $value5 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . $dataToInsert[2]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                }
                else{
                    $value5 = $value5 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                    $value5bis = $value5bis . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
                }
            }

            else{
                if ($resulttSalle2!=9){
                    $value5 = $value5 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . $dataToInsert[2]->salle. "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                }
                else{
                    $value5 = $value5 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "10". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                    $value5bis = $value5bis . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "11". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                }
            } // FIN else

        } // FIN du foreach ($verified2 as $verif)

        $value5 = substr($value5, 0, -2);
        if (isset($value5bis)) {
            $value5bis = substr($value5bis, 0, -2);
        }
    }

//___________________________________________

    if (isset($verified3)) {

        $value6 = "";
        if ($resulttSalle3==9){
            $value6bis = "";
        }

        foreach ($verified3 as $verif) {
            if (isset($verified2)){
                foreach ($verified2 as $verifPrecedente) {
                    if ($verif['salle'] == $verifPrecedente['salle'] ) {
                        if((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))){
                            if ( ($verif['debut']>=$verifPrecedente['debut'] && $verif['debut']<$verifPrecedente['fin']) || ($verif['fin']>= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])){
                                header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbidden");
                                exit();

                            }
                        }

                    }
                }
            }

            if (isset($verified1)){
                foreach ($verified1 as $verifPrecedente) {
                    if ($verif['salle'] == $verifPrecedente['salle'] ) {
                        if((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))){
                            if ( ($verif['debut']>=$verifPrecedente['debut'] && $verif['debut']<$verifPrecedente['fin']) || ($verif['fin']>= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])){
                                header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbidden");
                                exit();

                            }
                        }

                    }
                }
            }

            if (isset($verified0)){
                foreach ($verified0 as $verifPrecedente) {
                    if ($verif['salle'] == $verifPrecedente['salle'] ) {
                        if((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))){
                            if ( ($verif['debut']>=$verifPrecedente['debut'] && $verif['debut']<$verifPrecedente['fin']) || ($verif['fin']>= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])){
                                header("Location:index.php?page=modifCreneaux&modif_id=".$modif_id."&nbi=".$nbi."&forbidden");
                                exit();

                            }
                        }

                    }
                }
            }

            if ($verif['libre']==0){
                if($resulttSalle3!=9){
                    $value6 = $value6 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . $dataToInsert[3]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                } // FIN if
                else{
                    $value6 = $value6 . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                    $value6bis = $value6bis . " (NULL, '". $dateSeminaire . "', '" .$modif_id . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
                }
            }
            else{
                if ($resulttSalle3!=9){
                    $value6 = $value6 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . $dataToInsert[3]->salle. "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                }
                else{
                    $value6 = $value6 . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "10". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                    $value6bis = $value6bis . " (NULL, '". $dateSeminaire . "', NULL, NULL, '" . "11". "', '". $verif['creneau'] . "', NULL, NULL, '" . $verif['libre'] . "'), ";
                }
            } // FIN else

        } // FIN du foreach ($verified3 as $verif)

        $value6 = substr($value6, 0, -2);
        if (isset($value6bis)) {
            $value6bis = substr($value6bis, 0, -2);
        }
    }

//____________________________________________________________________________________________

    $value = substr($value, 0, -3);

    $sql2 = "INSERT INTO `sederouler` (`idSeminaire`, `idSalle`, `debut`, `fin`) VALUES $value ;";

    $sql3 = "INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value3.";";
    echo "<br/>";

    $clean3 = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='".$resulttSalle0."';";

    if (isset($value3bis)){
        $sql3bis = "INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value3bis.";";
        $clean3 = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 10 " ."';";
        $clean3bis = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 11 " ."';";
    }

    if (isset($value4)){
        $sql4 ="INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value4.";";
        $clean4="DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='".$resulttSalle1."';";

        if (isset($value4bis)) {
            $sql4bis = "INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value4bis.";";
            $clean4 = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 10 " ."';";
            $clean4bis = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 11 " ."';";
        }
    }
    if (isset($value5)){
        $sql5 ="INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value5.";";
        $clean5="DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='".$resulttSalle2."';";

        if (isset($value5bis)){
            $sql5bis = "INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value5bis.";";
            $clean5 = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 10 " ."';";
            $clean5bis = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 11 " ."';";
        }
    }
    if (isset($value6)){
        $sql6 ="INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value6.";";
        $clean6="DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='".$resulttSalle3."';";

        if (isset($value6bis)){
            $sql6bis = "INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value6bis.";";
            $clean6 = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 10 " ."';";
            $clean6bis = "DELETE FROM disponibilite WHERE dateSeminaire='".$dateSeminaire."' AND idSalle='". " 11 " ."';";
        }
    }

    // On insère les valeurs de la table sedérouler


    $result2 = $unController->execute($sql2);
    if ($result2==true){

        $delete1 = $unController->execute($clean3);

        if (isset($clean3bis)) { //Fermé

            $delete1bis = $unController->execute($clean3bis);
        } //Fin if isset clean3bis

        $premiereTranche = $unController->execute($sql3);
        $condition = "$"."premiereTranche==true";
        if (isset($clean3bis)){ //Fermé

            $condition = " && $"."premiereTrancheBis==true";
        } //Fin if isset clean3bis


        if (isset($sql4)){

            $delete2 = $unController->execute($clean4);
            if (isset($clean4bis)) { //Fermé

                $delete2bis = $unController->execute($clean4bis);
            } //Fin if isset clean4bis
            $secondeTranche = $unController->execute($sql4);
            if (isset($clean4bis)){ //Fermé

                $secondeTrancheBis = $unController->execute($sql4bis);
            } //Fin if isset clean4bis
            $condition = $condition . " && $"."secondeTranche==true";
            if (isset($clean4bis)){ //Fermé

                $condition = " && $"."secondeTrancheBis==true";
            } //Fin if isset clean4bis
        }

        if (isset($sql5)){

            $delete3 = $unController->execute($clean5);
            if (isset($clean5bis)) { //Fermé

                $delete5bis = $unController->execute($clean5bis);
            }//Fin if isset clean5bis
            $troisiemeTranche = $unController->execute($sql5);
            if (isset($clean5bis)){ //Fermé

                $troisiemeTrancheBis = $unController->execute($sql5bis);
            }//Fin if isset clean5bis
            $condition = $condition . " && $"."troisiemeTranche==true";
            if (isset($clean5bis)){ //Fermé

                $condition = " && $"."troisiemeTrancheBis==true";
            }

            if (isset($sql6)){
                $delete3 = $unController->execute($clean6);
                if (isset($clean6bis)) { //Fermé
                    $delete6bis = $unController->execute($clean6bis);
                }//Fin if isset clean6bis
                $quatriemeTranche = $unController->execute($sql6);
                if (isset($clean6bis)){ //Fermé
                    $quatriemeTrancheBis = $unController->execute($sql6bis);
                } //Fin if isset clean6bis
                $condition = $condition . " && $"."quatriemeTranche==true";
                if (isset($clean6bis)){ //Fermé
                    $condition = " && $"."quatriemeTrancheBis==true";
                }//Fin if isset clean6bis
            }

        } // FIN if isset sql5

        if ($condition==true){
            header("Location:index.php?page=modifSeminaire&modif_id=".$modif_id."&inserted");
            exit();

        }
    }// FIN if resultt2 == true

    else {
        foreach ($loopNb as $nb){
            $unController->execute("DELETE FROM sederouler WHERE idSeminaire='". $modif_id."' AND idSalle='".$dataToInsert[$nb]->salle."' AND debut='".$dataToInsert[$nb]->debut."';");
            $unController->execute("DELETE FROM disponibilite WHERE idSeminaire='". $modif_id."' AND idSalle='".$dataToInsert[$nb]->salle."' AND debutSeminaire='".$dataToInsert[$nb]->debut."';");
        }
        header("Location:index.php?page=modifSeminaire&modif_id=".$modif_id."&failure");
        exit();

    }



}//Fin POST

include 'header2.php';

?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h2 class="main_title">Modification des créneaux</h2>
        </div>
    </div>
</div>

<?php

if(isset($_GET['forbiddened']))
{
    ?>
    <div class="container" id="titleClient">
        <div class="alert alert-warning">
            <strong>Les horaires indiqués dans le formulaire sont incompatibles avec les séminaires déjà enregistrés.</strong>
        </div>
    </div>
    <?php
}
?>

<?php
if(isset($_GET['inserted'])){ ?>

    <div class="container" id="titleClient">
        <div class="alert alert-info">
            <strong>La modification a été effectuée !</strong><a href="index.php"> Accueil</a>
        </div>
    </div>
    <?php
}
else if(isset($_GET['failure']))
{
    ?>
    <div class="container" id="titleClient">
        <div class="alert alert-warning">
            <strong>Une erreur est survenue pendant la modification !</strong>
        </div>
    </div>
    <?php
}
else if(isset($_GET['forbidden']))
{
    ?>
    <div class="container" id="titleClient">
        <div class="alert alert-warning">
            <strong>Les créneaux indiqués ne sont pas compatibles !</strong>
        </div>
    </div>
    <?php
}?>

<div class="container" id="modifClient">

    <!-- Redirection finale -->
    <form method="POST" action="?page=modifCreneaux&modif_id=<?php print($_GET['modif_id']);?>&nbi=<?php print($_GET['nbi']);?>" id="selectClient">

        <!-- Redirection temporaire -->
        <!-- <form method="POST" action="?page=modifCreneaux&modif_id=<?php print($_GET['modif_id']);?>&nbi=<?php print($_GET['nbi']);?>" id="selectClient">
 -->
        <input type="hidden" name="ajoutCreneau">

        <input type="hidden" id="dateModif" class="form-control" name="dateSeminaire" value="<?php print($dateSeminaire);?>">

        <table class='table table-bordered'>

            <tr>
                <td>Créneau(x)</td>
                <td>


                    <div class="col-md-6 col-lg-6">


                        <?php
                        foreach ($listeDisponible as $uneDispo): ?>
                            <select id="<?php echo $uneDispo['id'] ?>" class="dateReserver" hidden>
                                <option class="idReserver" value="<?php echo $uneDispo['idSalle']; ?>"></option>
                                <option class="date"><?php echo $uneDispo['dateSeminaire']; ?></option>
                                <option class="debutCreneau"><?php echo $uneDispo['debutSeminaire']; ?></option>
                                <option class="finCreneau"><?php echo $uneDispo['finSeminaire']; ?></option>
                            </select>
                        <?php endforeach; ?>
                        <ul class="list-group">
                            <?php foreach ($listeDisponible as $uneDispo): ?>
                                <li id="<?php echo $uneDispo['id'] ?>" class="list-group-item hide idReservation" >
                                    <?php echo "<p class='leftParagraph'>Le séminaire : ".$uneDispo['nomSeminaire']."</p>"; ?>
                                    <p class="dateReservation" hidden><?php echo $uneDispo['dateSeminaire'] ?></p>
                                    <?php foreach ($listeSalles as $uneSalle):
                                        if ($uneSalle['idSalle'] == $uneDispo['idSalle']){
                                            echo "<p class='leftParagraph'>La salle : <span class='nomSalle'>".$uneSalle['libelle']."</span></p>";
                                        }
                                    endforeach; ?>
                                    <span class="badge topBadge"><?php echo $uneDispo['debutSeminaire']." - ". $uneDispo['finSeminaire']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
</div>
<div class="row" id="tableSalleDebutFin">
    <div class="col-md-12 col-lg-12">
        <table id="tableID" class="table" >
            <tr>
                <td>
                    <label>Salle : </label>

                    <span class="custom-dropdown custom-dropdown--white" id="selectSalle">
                                            <select id="BX_sal[]" name="BX_sal[]" class="custom-dropdown__select custom-dropdown__select--white" required >
                                                <option value=""></option>
                                                <?php foreach ($listeSalles as $uneSalle): ?>
                                                    <option value="<?php echo $uneSalle['idSalle'] ?>"><?php echo $uneSalle['libelle'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </span>

                </td>

                <td>
                    <label for="BX_debut[]">Début :</label>
                    <span class="custom-dropdown custom-dropdown--white" id="selectDebut">
                                            <select id="BX_debut[]" name="BX_debut[]" class="custom-dropdown__select custom-dropdown__select--white" required >
                                            <option value=""></option>
                                            <option value="00:00:00">00:00</option>
                                            <option value="00:30:00">00:30</option>
                                            <option value="01:00:00">01:00</option>
                                            <option value="01:30:00">01:30</option>
                                            <option value="02:00:00">02:00</option>
                                            <option value="02:30:00">02:30</option>
                                            <option value="03:00:00">03:00</option>
                                            <option value="03:30:00">03:30</option>
                                            <option value="04:00:00">04:00</option>
                                            <option value="04:30:00">04:30</option>
                                            <option value="05:00:00">05:00</option>
                                            <option value="05:30:00">05:30</option>
                                            <option value="06:00:00">06:00</option>
                                            <option value="06:30:00">06:30</option>
                                            <option value="07:00:00">07:00</option>
                                            <option value="07:30:00">07:30</option>
                                            <option value="08:00:00">08:00</option>
                                            <option value="08:30:00">08:30</option>
                                            <option value="09:00:00">09:00</option>
                                            <option value="09:30:00">09:30</option>
                                            <option value="10:00:00">10:00</option>
                                            <option value="10:30:00">10:30</option>
                                            <option value="11:00:00">11:00</option>
                                            <option value="11:30:00">11:30</option>
                                            <option value="12:00:00">12:00</option>
                                            <option value="12:30:00">12:30</option>
                                            <option value="13:00:00">13:00</option>
                                            <option value="13:30:00">13:30</option>
                                            <option value="14:00:00">14:00</option>
                                            <option value="14:30:00">14:30</option>
                                            <option value="15:00:00">15:00</option>
                                            <option value="15:30:00">15:30</option>
                                            <option value="16:00:00">16:00</option>
                                            <option value="16:30:00">16:30</option>
                                            <option value="17:00:00">17:00</option>
                                            <option value="17:30:00">17:30</option>
                                            <option value="18:00:00">18:00</option>
                                            <option value="18:30:00">18:30</option>
                                            <option value="19:00:00">19:00</option>
                                            <option value="19:30:00">19:30</option>
                                            <option value="20:00:00">20:00</option>
                                            <option value="20:30:00">20:30</option>
                                            <option value="21:00:00">21:00</option>
                                            <option value="21:30:00">21:30</option>
                                            <option value="22:00:00">22:00</option>
                                            <option value="22:30:00">22:30</option>
                                            <option value="23:00:00">23:00</option>
                                            <option value="23:30:00">23:30</option>
                                        </select>
                                        </span>
                </td>
                <td>
                    <label for="BX_fin[]">Fin :</label>

                    <span class="custom-dropdown custom-dropdown--white" id="selectFin">
                                            <select id="BX_fin[]" name="BX_fin[]" class="custom-dropdown__select custom-dropdown__select--white" required >
                                            <option value=""></option>
                                            <option value="00:00:00">00:00</option>
                                            <option value="00:30:00">00:30</option>
                                            <option value="01:00:00">01:00</option>
                                            <option value="01:30:00">01:30</option>
                                            <option value="02:00:00">02:00</option>
                                            <option value="02:30:00">02:30</option>
                                            <option value="03:00:00">03:00</option>
                                            <option value="03:30:00">03:30</option>
                                            <option value="04:00:00">04:00</option>
                                            <option value="04:30:00">04:30</option>
                                            <option value="05:00:00">05:00</option>
                                            <option value="05:30:00">05:30</option>
                                            <option value="06:00:00">06:00</option>
                                            <option value="06:30:00">06:30</option>
                                            <option value="07:00:00">07:00</option>
                                            <option value="07:30:00">07:30</option>
                                            <option value="08:00:00">08:00</option>
                                            <option value="08:30:00">08:30</option>
                                            <option value="09:00:00">09:00</option>
                                            <option value="09:30:00">09:30</option>
                                            <option value="10:00:00">10:00</option>
                                            <option value="10:30:00">10:30</option>
                                            <option value="11:00:00">11:00</option>
                                            <option value="11:30:00">11:30</option>
                                            <option value="12:00:00">12:00</option>
                                            <option value="12:30:00">12:30</option>
                                            <option value="13:00:00">13:00</option>
                                            <option value="13:30:00">13:30</option>
                                            <option value="14:00:00">14:00</option>
                                            <option value="14:30:00">14:30</option>
                                            <option value="15:00:00">15:00</option>
                                            <option value="15:30:00">15:30</option>
                                            <option value="16:00:00">16:00</option>
                                            <option value="16:30:00">16:30</option>
                                            <option value="17:00:00">17:00</option>
                                            <option value="17:30:00">17:30</option>
                                            <option value="18:00:00">18:00</option>
                                            <option value="18:30:00">18:30</option>
                                            <option value="19:00:00">19:00</option>
                                            <option value="19:30:00">19:30</option>
                                            <option value="20:00:00">20:00</option>
                                            <option value="20:30:00">20:30</option>
                                            <option value="21:00:00">21:00</option>
                                            <option value="21:30:00">21:30</option>
                                            <option value="22:00:00">22:00</option>
                                            <option value="22:30:00">22:30</option>
                                            <option value="23:00:00">23:00</option>
                                            <option value="23:30:00">23:30</option>
                                        </select>
                                        </span>
                </td>
            </tr>
        </table>
    </div>
</div>

</td>
</tr>

</tbody>
</table>

<button type="submit" class="btn btn-primary" name="submit">
    <span class="glyphicon glyphicon-edit"></span> Réserver
</button>

<a href="?page=modifSeminaire&modif_id=<?=$modif_id?>" class="btn btn-large btn-success"><i class="glyphicon glyphicon-backward"></i> &nbsp; Retour</a>

</form>

</div>