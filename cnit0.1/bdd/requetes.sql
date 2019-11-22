-- SELECTION SEMINAIRE EN COURS PAR ID SALLE
-- !! AJOUTER RESTRICTION DATE

SELECT time_format(debut, '%H:%i') AS leDebut, time_format(fin, '%H:%i') AS laFin, seminaire.idSeminaire AS idSeminaire, seminaire.nomSeminaire AS nomSeminaire, salle.libelle AS libelle, salle.fonction AS fonction, COUNT(*) AS total 
        FROM sederouler
        INNER JOIN seminaire
        ON sederouler.idSeminaire = seminaire.idSeminaire
        INNER JOIN salle
        ON sederouler.idSalle = salle.idSalle 
        WHERE sederouler.idSalle = 1
        AND NOW() BETWEEN DATE_SUB(sederouler.debut, INTERVAL 15 MINUTE) 
        AND (DATE_SUB(sederouler.fin,INTERVAL 10 MINUTE))
        AND seminaire.dateSeminaire = CURDATE()
        GROUP BY leDebut, laFin, idSeminaire, nomSeminaire, libelle, fonction;

SELECT time_format(debut, '%H:%i') AS leDebut, time_format(fin, '%H:%i') AS laFin, seminaire.idSeminaire AS idSeminaire, seminaire.nomSeminaire AS nomSeminaire, salle.libelle AS libelle, salle.fonction AS fonction, COUNT(*) AS total 
        FROM sederouler
        INNER JOIN seminaire
        ON sederouler.idSeminaire = seminaire.idSeminaire
        INNER JOIN salle
        ON sederouler.idSalle = salle.idSalle 
        WHERE sederouler.idSalle =".$id."
        AND NOW() BETWEEN DATE_SUB(sederouler.debut, INTERVAL 15 MINUTE) 
        AND (DATE_SUB(sederouler.fin,INTERVAL 10 MINUTE))
        GROUP BY leDebut, laFin, idSeminaire, nomSeminaire, libelle, fonction;


-- RECHERCHE DE PERIODES PAR SEMINAIRE

SELECT time_format(debut, '%H:%i') AS debut, time_format(fin, '%H:%i') AS fin, nomSeminaire, salle.libelle AS libelle, salle.fonction AS fonction, COUNT(*) AS total 
        FROM sederouler
        INNER JOIN seminaire
        ON sederouler.idSeminaire = seminaire.idSeminaire
        INNER JOIN salle
        ON sederouler.idSalle = salle.idSalle 
        WHERE seminaire.idSeminaire = 1
        AND NOW() BETWEEN DATE_SUB(sederouler.debut, INTERVAL 15 MINUTE) 
        AND sederouler.fin
        GROUP BY debut, fin, nomSeminaire, libelle, fonction        

        AND NOW() BETWEEN DATE_SUB(sederouler.debut, INTERVAL 15 MINUTE) AND sederouler.fin

      $req = $db->prepare('SELECT sa.libelle, c.nom, c.logo, s.nomSeminaire, f.fonction 
        FROM Client c, Seminaire se, SeDerouler sd, Salle sa, Fonction f 
        WHERE idSalle = :id
        AND NOW() BETWEEN (DATE_SUB(se.debut, INTERVAL 15 MINUTE) AND (DATE_SUB(se.fin, INTERVAL 10 MINUTE)
        AND se.dateSeminaire = CURDATE()
        AND f.idFonction = sd.idFonction
        AND sd.idSalle= sa.idSalle
        AND se.idSeminaire=sd.idSeminaire
        AND se.idClient = c.idClient');



      $req = $db->prepare("SELECT sa.libelle AS libelle, c.libelle AS nom, c.logo, se.idSeminaire, se.nomSeminaire, f.fonction FROM seminaire se, salle sa, sederouler sd, client c, fonction f, periode p, lieu l
        WHERE sa.idSalle = $id 
        AND se.idSeminaire = sd.idSeminaire
        AND se.dateSeminaire =  CURDATE()
        AND c.idClient = se.idClient
        AND sd.idLieu = l.idLieu
        AND l.idSalle = sa.idSalle
        AND l.idFonction = f.fonction");

      $req->execute();
      $seminaire = $req->fetch();

      return new Seminaire($seminaire['libelle'], $seminaire['nom'], $seminaire['logo'], $seminaire['idSeminaire'], $seminaire['nomSeminaire'], $seminaire['fonction']);



Extension BDD : 

#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: periode
#------------------------------------------------------------

CREATE TABLE periode(
        idPeriode int (11) Auto_increment  NOT NULL ,
        idFin     Int NOT NULL ,
        idDebut   Int NOT NULL ,
        PRIMARY KEY (idPeriode )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: heure
#------------------------------------------------------------

CREATE TABLE heure(
        idHeure int (11) Auto_increment  NOT NULL ,
        heure   Time NOT NULL ,
        PRIMARY KEY (idHeure )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: debut
#------------------------------------------------------------

CREATE TABLE debut(
        idDebut int (11) Auto_increment  NOT NULL ,
        idHeure Int NOT NULL ,
        PRIMARY KEY (idDebut )
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: fin
#------------------------------------------------------------

CREATE TABLE fin(
        idFin   int (11) Auto_increment  NOT NULL ,
        idHeure Int NOT NULL ,
        PRIMARY KEY (idFin )
)ENGINE=InnoDB;

ALTER TABLE periode ADD CONSTRAINT FK_Periode_idFin FOREIGN KEY (idFin) REFERENCES Fin(idFin);
ALTER TABLE periode ADD CONSTRAINT FK_Periode_idDebut FOREIGN KEY (idDebut) REFERENCES Debut(idDebut);
ALTER TABLE debut ADD CONSTRAINT FK_Debut_idHeure FOREIGN KEY (idHeure) REFERENCES Heure(idHeure);
ALTER TABLE fin ADD CONSTRAINT FK_Fin_idHeure FOREIGN KEY (idHeure) REFERENCES Heure(idHeure);

    