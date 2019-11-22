<?php

if(isset($_POST['submit'])){ //Check

    $pipe = strpbrk($_POST['nomClient'], "|");
    if ($pipe==false) {
        header("Location:index.php?page=addSeminaire&unknown");
        exit();
    }

    if ($_POST['dateSeminaire']<date("Y-m-d")){
        header("Location:index.php?page=addSeminaire&wrongdate");
        exit();
    }

    list($idClient, $nomClient)=explode("|", $_POST['nomClient']);
    $nomSeminaire=$_POST['nomSeminaire'];
    $dateSeminaire=$_POST['dateSeminaire'];
    $affNom=$_POST['affNom'];
    if ($_POST['message'] == " "){$message="NULL";}
    else{$message="'".$_POST['message']."'";}
    $BX_salle=$_POST['BX_salle'];
    $BX_debut=$_POST['BX_debut'];
    $BX_fin=$_POST['BX_fin'];

    $clientExiste = $unController->selectThatWhere("client", "count(*)", "id= '".$idClient."'");
    if ($clientExiste[0]=0){
        header("Location:index.php?page=addSeminaire&unknown");
        exit();
    }

    $countRow = count($BX_salle);

    $dataToInsert = array();
    $loopNb = array();
//LoopNb et countRow représentent le nombre de tranches horaires à insérer
    for ($i=0; $i<$countRow; $i++) {
        $loopNb[] = $i;
    }

    foreach ($loopNb as $nb) {
        ${'salle' . $nb} = $BX_salle[$nb];
    }
    foreach ($loopNb as $nb) {
        ${'debut' . $nb} = $BX_debut[$nb];
    }
    foreach ($loopNb as $nb) {
        ${'fin' . $nb} = $BX_fin[$nb];
    }

// La variable dataToInsert contient les informations relatives à chaque créneau à insérer sous forme de tableau
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
                    header("Location:index.php?page=addSeminaire&forbidden");
                    exit();
                    //echo "cas1";

                }
            }
        }
    }

    $value = "";
//INFOS GENERALES DU SEMINAIRE (SANS CRENEAUX)
    $sql = "INSERT INTO `seminaire` (`idSeminaire`, `nomSeminaire`, `dateSeminaire`, `affNom`, `idClient`, `message`) VALUES (NULL, '$nomSeminaire', '$dateSeminaire', '$affNom', '$idClient', $message)";

//PREMIERE REQUETE ET RECUPERATION DE L'ID CLIENT
    $result = $unController->getLastId($sql);

    if($result ){ //!= false
        $seminaireId = $result;


        foreach ($loopNb as $nb){
            $value = $value . "(" . "'" .$seminaireId . "'" . ", " . "'" . $dataToInsert[$nb]->salle . "'" . ", " . "'" . $dataToInsert[$nb]->debut . "'" . ", " . "'" . $dataToInsert[$nb]->fin . "'" . ") , ";

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

                            // ${'resultt' . $nb} = $unController->verify($dateSeminaire, 11);
                        } //Fin foreach

                    } // FIN if !empty $moduVerif
                }

                ${'resulttSalle' . $nb} = $dataToInsert[$nb]->salle;
                // var_dump(${'resulttSalle' . $nb});
            }
            // var_dump($resultt0);
            // var_dump($dataToInsert);
            if (isset($resultt1)) {
                // var_dump($resultt0); //09:30->10:00 V

                if ($resulttSalle0==$resulttSalle1){
                    foreach ($resultt1 as $result){
                        // var_dump($result);
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
                    $destroy1=0;
                }
                // var_dump($resultt0);
            }
            // var_dump($resultt0);

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
                    $destroy2=0;
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
                        // var_dump($nb);
                        if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                            // var_dump($nb);
                            // var_dump(date('H:i', strtotime($creneauRef)));
                            // var_dump($dataToInsert[$nb]);
                            $verified0[$j]['seminaireId']=$seminaireId;
                            $verified0[$j]['debut']=$dataToInsert[$nb]->debut;
                            $verified0[$j]['fin']=$dataToInsert[$nb]->fin;
                            $verified0[$j]['libre']=0;




                        }  //FIN id creneauRef entre debut et fin seminaire.

                        // if (isset($resultt0[0]['salle'])) {
                        //     if (!(isset($verified0[$j]['salle']))) {
                        //         $verified0[$j]['salle']=$resultt0[0]['salle'];
                        //     }
                        // }

                        $verified0[$j]['salle']=$resulttSalle0;

                    }//FIN if (isset($resultt0[$i])){

                    if (isset($verified1[$i])){

                        if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                            $verified1[$j]['seminaireId']=$seminaireId;
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

                    } //FIN if (isset($resultt1[$i])){

                    if (isset($verified2[$i])){

                        if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                            $verified2[$j]['seminaireId']=$seminaireId;
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

                    } //FIN if (isset($resultt2[$i])){

                    if (isset($verified3[$i])){

                        if (date('H:i', strtotime($creneauRef)) >= date('H:i', strtotime($dataToInsert[$nb]->debut)) && date('H:i', strtotime($creneauRef)) < date('H:i',strtotime($dataToInsert[$nb]->fin)) ){
                            $verified3[$j]['seminaireId']=$seminaireId;
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

                    } //FIN if (isset($resultt3[$i])){


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

        //var_dump($verified0);

        //ON AFFECTE LES VALEURS DU SEMINAIRE AJOUTE A L'ARRAY $VERIFIED
        foreach ($loopNb as $nb) {
            $creneauRef = '00:00:00';

            for ($j=0; $j<48; $j++) {

                if (isset($resultt0) && !empty($resultt0)){

                    $getInfoCreneau = $unController->search($resultt0, "creneau", $creneauRef);

                    if (!(empty($getInfoCreneau))){

                        if ($getInfoCreneau['libre']==0 && $getInfoCreneau['idSeminaire']!=$seminaireId && !(is_null($getInfoCreneau['idSeminaire']))) {

                            header("Location:index.php?page=addSeminaire&forbiddened");
                            exit();
                        }
                    }
                    else{

                        if (isset($resultt0[$j]) && $resultt0[$j]['libre']==1 && !(is_null($resultt0[$j]['idSeminaire'])) && $resultt0[$j]['idSeminaire']!=$seminaireId ) {

                            $verified0[$j]['seminaireId']=$resultt0[$j]['idSeminaire'];
                            $verified0[$j]['debut']=$resultt0[$j]['debut'];
                            $verified0[$j]['fin']=$resultt0[$j]['fin'];
                            $verified0[$j]['libre']=0;
                        }
                    }

                }//FIN if isset resultt0

                if (isset($resultt1) && !empty($resultt1)){

                    $getInfoCreneau = $unController->search($resultt1, "creneau", $creneauRef);

                    if (!(empty($getInfoCreneau))){

                        if ($getInfoCreneau['libre']==0 && $getInfoCreneau['idSeminaire']!=$seminaireId && !(is_null($getInfoCreneau['idSeminaire']))) {

                            header("Location:index.php?page=addSeminaire&forbiddened");
                            exit();
                        }
                    }
                    else{
                        if (isset($resultt1[$j]) && $resultt1[$j]['libre']==1 && !(is_null($resultt1[$j]['idSeminaire'])) && $resultt1[$j]['idSeminaire']!=$seminaireId ) {

                            $verified1[$j]['seminaireId']=$resultt1[$j]['idSeminaire'];
                            $verified1[$j]['debut']=$resultt1[$j]['debut'];
                            $verified1[$j]['fin']=$resultt1[$j]['fin'];
                            $verified1[$j]['libre']=0;
                        }
                    }
                }

                //FIN if isset resultt1

                if (isset($resultt2) && !empty($resultt2)){

                    $getInfoCreneau = $unController->search($resultt2, "creneau", $creneauRef);

                    if (!(empty($getInfoCreneau))){

                        if ($getInfoCreneau['libre']==0 && $getInfoCreneau['idSeminaire']!=$seminaireId && !(is_null($getInfoCreneau['idSeminaire']))) {
                            header("Location:index.php?page=addSeminaire&forbiddened");
                            exit();
                        }
                    }
                    else{
                        if (isset($resultt2[$j]) && $resultt2[$j]['libre']==1 && !(is_null($resultt2[$j]['idSeminaire'])) && $resultt2[$j]['idSeminaire']!=$seminaireId ) {

                            $verified2[$j]['seminaireId']=$resultt2[$j]['idSeminaire'];
                            $verified2[$j]['debut']=$resultt2[$j]['debut'];
                            $verified2[$j]['fin']=$resultt2[$j]['fin'];
                            $verified2[$j]['libre']=0;
                        }
                    }
                }

                //FIN if isset resultt2

                if (isset($resultt3) && !empty($resultt3)){
                    $getInfoCreneau = $unController->search($resultt3, "creneau", $creneauRef);

                    if (!(empty($getInfoCreneau))){

                        if ($getInfoCreneau['libre']==0 && $getInfoCreneau['idSeminaire']!=$seminaireId && !(is_null($getInfoCreneau['idSeminaire']))) {
                            header("Location:index.php?page=addSeminaire&forbiddened");
                            exit();
                        }
                    }
                    else{
                        if (isset($resultt3[$j]) && $resultt3[$j]['libre']==1 && !(is_null($resultt3[$j]['idSeminaire'])) && $resultt3[$j]['idSeminaire']!=$seminaireId ) {

                            $verified3[$j]['seminaireId']=$resultt3[$j]['idSeminaire'];
                            $verified3[$j]['debut']=$resultt3[$j]['debut'];
                            $verified3[$j]['fin']=$resultt3[$j]['fin'];
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
        // var_dump($verified0);

        foreach ($verified0 as $verif) {
            if (isset($verif['fin']) && $verif['debut'] && $verif['fin'] <= $verif['debut']){
                header("Location:index.php?page=addSeminaire&impossible");
                exit();
            }

            if ($verif['libre']==0){
                if($resulttSalle0!=9){
                    $value3 = $value3 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . $dataToInsert[0]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                }
                else{
                    $value3 = $value3 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                    $value3bis = $value3bis . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
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
            if ($resulttSalle1=9){
                $value4bis = "";
            }
            // var_dump($verified0);
            // var_dump($verified1);
            foreach ($verified1 as $verif) {
                if (isset($verified0)){
                    foreach ($verified0 as $verifPrecedente) {

                        if ($verif['salle'] == $verifPrecedente['salle'] ) {
                            if ((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))) {
                                if (($verif['debut'] >= $verifPrecedente['debut'] && $verif['debut'] < $verifPrecedente['fin']) || ($verif['fin'] >= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])) {
                                    header("Location:index.php?page=addSeminaire&forbidden");
                                    exit();
                                    //echo "cas2";
                                }
                            }
                        }
                    }
                }

                if ($verif['libre']==0){
                    if($resulttSalle1!=9){
                        $value4 = $value4 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . $dataToInsert[1]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                    }
                    else{
                        $value4 = $value4 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                        $value4bis = $value4bis . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
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

            } // FIN du foreach ($verified0 as $verif)

            $value4 = substr($value4, 0, -2);
            echo "</br>";
            // var_dump($verified1);
            if (isset($value4bis)) {
                $value4bis = substr($value4bis, 0, -2);
            }
        } //if isset $verified1


//___________________________________________

        if (isset($verified2)) {

            $value5 = "";
            if ($resulttSalle2=9){
                $value5bis = "";
            }

            $value5 = "";
            foreach ($verified2 as $verif) {
                if (isset($verified1)) {
                    foreach ($verified1 as $verifPrecedente) {
                        if ($verif['salle'] == $verifPrecedente['salle']) {
                            if ((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))) {
                                if (($verif['debut'] >= $verifPrecedente['debut'] && $verif['debut'] < $verifPrecedente['fin']) || ($verif['fin'] >= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])) {
                                    header("Location:index.php?page=addSeminaire&forbidden");
                                    exit();
                                    //echo "cas3";
                                }
                            }

                        }
                    }
                }

                if (isset($verified0)) {
                    foreach ($verified0 as $verifPrecedente) {
                        if ($verif['salle'] == $verifPrecedente['salle']) {
                            if (($verif['debut'] >= $verifPrecedente['debut'] && $verif['debut'] < $verifPrecedente['fin']) || ($verif['fin'] >= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])) {
                                header("Location:index.php?page=addSeminaire&forbidden");
                                exit();
                                //echo "cas4";
                            }

                        }
                    }
                }

                if ($verif['libre']==0){
                    if($resulttSalle2!=9){
                        $value5 = $value5 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . $dataToInsert[2]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                    }
                    else{
                        $value5 = $value5 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                        $value5bis = $value5bis . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
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
        } // if isset $verified2

//___________________________________________

        if (isset($verified3)) {

            $value6 = "";
            if ($resulttSalle3==9){
                $value6bis = "";
            }

            foreach ($verified3 as $verif) {
                if (isset($verified0)) {
                    foreach ($verified0 as $verifPrecedente) {
                        if ($verif['salle'] == $verifPrecedente['salle']) {
                            if ((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))) {
                                if (($verif['debut'] >= $verifPrecedente['debut'] && $verif['debut'] < $verifPrecedente['fin']) || ($verif['fin'] >= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])) {
                                    header("Location:index.php?page=addSeminaire&forbidden");
                                    exit();

                                }
                            }

                        }
                    }
                }

                if (isset($verified1)) {
                    foreach ($verified1 as $verifPrecedente) {
                        if ($verif['salle'] == $verifPrecedente['salle']) {
                            if ((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))) {
                                if (($verif['debut'] >= $verifPrecedente['debut'] && $verif['debut'] < $verifPrecedente['fin']) || ($verif['fin'] >= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])) {
                                    header("Location:index.php?page=addSeminaire&forbidden");
                                    exit();

                                }
                            }

                        }
                    }
                }

                if (isset($verified2)) {
                    foreach ($verified2 as $verifPrecedente) {
                        if ($verif['salle'] == $verifPrecedente['salle']) {
                            if ((isset($verif['debut'])) && (isset($verif['fin'])) && (isset($verifPrecedente['debut'])) && (isset($verifPrecedente['fin']))) {
                                if (($verif['debut'] >= $verifPrecedente['debut'] && $verif['debut'] < $verifPrecedente['fin']) || ($verif['fin'] >= $verifPrecedente['debut'] && $verif['fin'] <= $verifPrecedente['fin'])) {
                                    header("Location:index.php?page=addSeminaire&forbidden");
                                    exit();

                                }
                            }

                        }
                    }
                }

                if ($verif['libre']==0){
                    if($resulttSalle3!=9){
                        $value6 = $value6 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . $dataToInsert[3]->salle. "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                    } // FIN if
                    else{
                        $value6 = $value6 . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "10". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), ";
                        $value6bis = $value6bis . " (NULL, '". $dateSeminaire . "', '" .$seminaireId . "', '". $nomSeminaire. "', '" . "11". "', '". $verif['creneau'] . "', '". $verif['debut'] . "', '". $verif['fin'] . "', '" . $verif['libre'] . "'), "  ;
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
        } //if isset $verified3

//____________________________________________________________________________________________

        $value = substr($value, 0, -3);

        $sql2 = "INSERT INTO `sederouler` (`idSeminaire`, `idSalle`, `debut`, `fin`) VALUES $value ;";

        $sql3 = "INSERT INTO `disponibilite` (`id`, `dateSeminaire`, `idSeminaire`, `nomSeminaire`, `idSalle`, `creneau`, `debutSeminaire`, `finSeminaire`, `libre`) VALUES ".$value3.";";
        //print_r($sql);

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
        if ($result2){//==true){ //Fermé

            // Puis on insère les valeurs dans la table disponibilités
            $delete1 = $unController->execute($clean3);
            // var_dump($delete1);
            if (isset($clean3bis)) { //Fermé
                $delete1bis = $unController->execute($clean3bis);
                // var_dump($delete1bis);
            } //Fin if isset clean3bis
            $premiereTranche = $unController->execute($sql3);
            // print_r($premiereTranche);
            if (isset($clean3bis)){ //Fermé
                $premiereTrancheBis = $unController->execute($sql3bis);
                // print_r($premiereTrancheBis);
            } //Fin if isset clean3bis
            $condition = "$"."premiereTranche==true";
            if (isset($clean3bis)){ //Fermé
                $condition = " && $"."premiereTrancheBis==true";
            } //Fin if isset clean3bis

            if (isset($sql4)){ //Fermé
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
            } //Fermé

            if (isset($sql5)){ //Fermé
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
                }//Fin if isset clean5bis
            } //if isset clean5bis

            if (isset($sql6)){ //Fermé
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
            }//FIN if isset $sql6

            // var_dump($condition);
            // var_dump($condition == true);
            if ($condition){//Fermé
                header("Location:index.php?page=addSeminaire&inserted");
                exit();
                var_dump($sql2);

                // var_dump($dataToInsert);
                // var_dump($resultt0);
                // $kek = get_defined_vars();
                // var_dump($kek);

            } //Fin if condition

            else{ //Fermé
                $unController->eraseSeminaire($seminaireId);
                $unController->eraseDerouler($seminaireId);
                $unController->eraseDispo($seminaireId);
                echo "erase1";
                header("Location:index.php?page=addSeminaire&failure");
                exit();
            } //Fin else

        } // FIN if resultt2 == true

        else{ //Fermé
            $unController->eraseSeminaire($seminaireId);
            $unController->eraseDerouler($seminaireId);
            $unController->eraseDispo($seminaireId);
            echo "erase2";
            header("Location:index.php?page=addSeminaire&failure");
            exit();
        } //Fin else

    }
// var_dump($resultt0);
// var_dump($resultt1);
// var_dump($resultt2);
// var_dump($resultt3);
}

include ("header.php");

?>

<div class="container" id="titleClient">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <h2 class="main_title">Ajout d'un séminaire</h2>
        </div>
    </div>
</div>

<!-- Vérification et affichage de la réponse si l'ajout a été effecctué ou non -->
<?php if(isset($_GET['inserted'])){ ?>
        <div class="container alert alert-info">
            <strong>La réservation a été effectuée !</strong>
            <a href="?page=reservation" class="linkHome"> Accueil</a>
        </div>
    <?php
}
else if(isset($_GET['failure']))
{
    ?>
        <div class="container alert alert-warning">
            <strong>Une erreur est survenue pendant l'ajout !</strong>
        </div>
    <?php
}
else if(isset($_GET['forbiddened']))
{
    ?>
        <div class="container alert alert-warning">
            <strong>Les horaires indiqués dans le formulaire sont incompatibles avec les séminaires déjà enregistrés.</strong>
        </div>
    <?php
}
else if(isset($_GET['unknown']))
{
    ?>
        <div class="container alert alert-warning">
            <strong>Le client indiqué n'existe pas.</strong>
        </div>
    <?php
}
?>

<!-- Formulaire de l'ajout d'un séminaire -->
<div class="container" id="addClient">

    <form method="POST" action="?page=addSeminaire" id="selectClient">
        <div class="form-group">

        </div>

        <!-- Champ de recherche d'un client -->
        <div class="row" id="searchClientSeminaire">
            <div class="col-md-1 col-lg-1" id="labelClient">
                <label>Client : </label>
            </div>
            <div class="col-md-5">
                <datalist id="clients" name="client">
                    <?php foreach ($listeClients as $unClient):?>
                        <option value="<?= $unClient['id']; ?> | <?= $unClient['libelle'] ?>"></option>
                    <?php endforeach; ?>
                </datalist>
                <input type="text" placeholder="Entrer le client dans la liste" list="clients" name="nomClient" value="<?php if (isset($_POST['informationsClient']) ){ echo $_POST['infoIdClient']." | ". $_POST['infoNomClient']; } ?>" class="form-control" autocomplete="off" required>
            </div>
        </div>

        <!-- Tableau du formulaire d'ajout du nom du séminaire, de la date et du ou des créneau(x) -->
        <table class='table table-bordered'>
            <tr>
                <td class="thSeminaire">Intitulé du séminaire </td>
                <td><input type='text' placeholder="100 caratères maximum" name='nomSeminaire' class='form-control' maxlength="100" required></td>
            </tr>
            <tr>
                <td class="thSeminaire">Date</td>
                <td>
                    <input type="text" placeholder="Choisissez votre date de réservation" id="datepicker" class="form-control" name="dateSeminaire">
                </td>
            </tr>
            <tr>
                <td class="thSeminaire">Affichage</td>
                <td id="cellReservationleft">
                    <span class="custom-dropdown custom-dropdown--white" id="ouiNon">
                        <select name="affNom" class="custom-dropdown__select custom-dropdown__select--white" required>
                        <option value="1" selected>Oui</option>
                        <option value="0">Non</option>
                    </select>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="thSeminaire">Message</td>
                <td><input type='text' name='message' class='form-control' maxlength="200" placeholder="Facultatif"></td>
            </tr>
            <tr>
                <td class="thSeminaire">Créneau(x)</td>
                <td>
                    <div class="row topDateReserver">
                        <!-- Liste de vérification des séminaire du jour -->
                        <div class="col-md-12 col-lg-12">
                            <ul class="list-group">
                                <?php foreach ($listeDisponible as $uneDispo): ?>
                                    <li id="<?php echo $uneDispo['dateSeminaire'] ?>" class="list-group-item hide idReservation" >
                                        <?php echo "<p class='leftParagraph'>Le séminaire : ".$uneDispo['nomSeminaire']."</p>"; ?>
                                        <p class="dateReservation leftParagraph"><?php echo $uneDispo['dateSeminaire'] ?></p>
                                        <?php foreach ($listeSalles as $uneSalle):
                                            if ($uneSalle['idSalle'] == $uneDispo['idSalle']){
                                                echo "<p class='leftParagraph'>La salle : <span class='nomSalle'>".$uneSalle['libelle']."</span></p>";
                                            }
                                        endforeach; ?>
                                        <span class="badge leftParagraph"><?php echo $uneDispo['debutSeminaire']." - ". $uneDispo['finSeminaire']; ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                    </div>
                    <div class="row" id="tableSalleDebutFin">
                        <div class="col-md-12 col-lg-12">
                            <table id="tableID" class="table" >
                                <tr>
                                    <td>
                                        <label class="thSeminaire">Salle : </label>
                                        <!-- Liste de choix des salles -->
                                        <span class="custom-dropdown custom-dropdown--white" id="selectSalle">
                                            <select id="BX_salle[]" name="BX_salle[]" class="custom-dropdown__select custom-dropdown__select--white" required disabled>
                                                <option value=""></option>
                                                <?php foreach ($listeSalles as $uneSalle): ?>
                                                <option value="<?php echo $uneSalle['idSalle'] ?>"><?php echo $uneSalle['libelle'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </span>

                                    </td>

                                    <td>
                                        <!-- Liste de choix du déut du ou des  créneau(x) -->
                                        <label class="thSeminaire" for="BX_debut[]">Début :</label>
                                        <span class="custom-dropdown custom-dropdown--white" id="selectDebut">
                                            <select id="BX_debut[]" name="BX_debut[]" class="custom-dropdown__select custom-dropdown__select--white" required disabled>
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
                                        <label class="thSeminaire" for="BX_fin[]">Fin :</label>
                                        <!-- Liste de choix de fin du ou des créneaux -->
                                        <span class="custom-dropdown custom-dropdown--white" id="selectFin">
                                            <select id="BX_fin[]" name="BX_fin[]" class="custom-dropdown__select custom-dropdown__select--white" required disabled>
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
        </table>

        <button type="submit" class="btn btn-info" name="submit">
            <span class="glyphicon glyphicon-edit"></span> Réserver
        </button>
        <a href="index.php" class="btn btn-large btn-success" id="return">
            <i class="glyphicon glyphicon-backward"></i>
            &nbsp; Retour
        </a>
    </form>
</div>

