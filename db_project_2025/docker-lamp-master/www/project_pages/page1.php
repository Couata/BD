Dexter
dexter201_
Unsichtbar

Dexter — gestern um 22:36 Uhr
*myphpadmin
Couata — gestern um 22:37 Uhr
Ah vraiment ?
Dexter — gestern um 22:41 Uhr
ben oui
tu peux faire des queries pour tester mais c'est la page internet qui compte
Couata — 11:14
voilà ce que j'ai fait en sql, mais je ne sais pas quoi faire d'autre
USE group7;

-- ------------------------------------------------------
-- Creation du view sans prendre en compte les exceptions
-- ------------------------------------------------------
Mehr anzeigen
message.txt
3 kB
Dexter — 11:15
ben tu mes ce que tu as besoin et ce sera bon👍
je vais ajouter des trigggers en plus, il faut absolument qu'on fasse attention au contrainte d'integrité comme 1:n 1;2 n:n , bien verifier a chaque update et insertion qu'il y a pas des id double et des trucs comme ca
c'est mon job d'aujourdui, ducoup faut faire des try catch (exception) si tu fais des insertion et update
Couata — 11:17
okok, ma page 2 incrémente automatiquement l'id du coup il n'y a pas de problème
Dexter — 11:17
géniale
oui j'ai changé le type
Couata — 11:17
en tout cas je n'ai pas trouvé de problème
Dexter — 11:17
comme tu avais dis
ok ok ca va alors
Couata — 11:18
oui c'est pour ça que ça marche
Dexter — 11:18
en effet
Dexter — 11:25
tu as le reste du code de la page3?
Couata — 11:26
J'étais entrain de le faire
Dexter — 11:26
🥰
Couata — 11:26
Je te l'envoie dès que ça marche
Couata — 13:59
mec j'ai tjr cette erreure de merde
Fatal error: Uncaught PDOException: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'group7.services_par_date_final' doesn't exist in /var/www/html/project_pages/page3.php:34 Stack trace: #0 /var/www/html/project_pages/page3.php(34): PDO->prepare('SELECT * FROM s...') #1 {main} thrown in /var/www/html/project_pages/page3.php on line 34
je ne comprends pas
Dexter — 14:04
'group7.services_par_date_final' 

vérifie le nom de ta vue
peut être le string est faux
Couata — 14:37
CREATE VIEW services_par_date_final AS
Couata — 15:16
SHOW FULL TABLES IN group7 WHERE TABLE_TYPE LIKE 'VIEW';
chatgpt me dit de mettre ça, sur mysqladmin
Dexter — 15:17
Je ne comprend pas , pour quelle raison on doit utiliser mysqladmin en fait?
Couata — 15:17
moi non-plus
Dexter — 15:17
Pour debugger
Il te montre le contenu de ta db
Couata — 15:17
oui mais je ne sais même pas ou le mettre
Dexter — 15:17
Mettre quoi?
Couata — 15:18
ben cette ligne, je dois la mettre quelque part dans mysqladmin
Dexter — 15:19
Mec l'erreur
Veux juste dire que ta vue existe pas , le nom n'existe pas
En effet elle n'est pas dans tes vues que tu pa envoyer
Couata — 15:20
je l'ai modifié justement
Dexter — 15:20
Ah
Tu pourrais envoyer ?
Couata — 15:20
regarde tout en bas
USE group7;

-- ------------------------------------------------------
-- Creation du view sans prendre en compte les exceptions
-- ------------------------------------------------------
Mehr anzeigen
message.txt
3 kB
Dexter — 15:21
Et il y a une nouvelle erreur?
Phpadmin c'est que pour debugger et vérifier ta base de données. 
Il peut générer du code sql sans pouvoir l'écrire mais il faudra quand même le recordé dans le code même de notre projet. Donc on s'en fou de phpadmin
C'est dans ton code du site que sa bug
Couata — 15:25
Ah ok c'est comme ça
$query = "SELECT * FROM services_par_date_final 
                            WHERE date_service BETWEEN :date_debut AND :date_fin";
voilà commen je fais ma demande, et j'ai bien vérifier que date_service et service_par_date_final soit correcte
Dexter — 15:33
Il rouspète encore où ça passe?
Couata — 15:34
il rouspete toujours ce connard de code de lerde
mais tkt je vais réussir à trouver
Dexter — 15:35
ce code de lerde$
Couata — 15:35
merde* 😂
Dexter — 15:36
Je vais travailer sur le rapport apres aussi, c'est 2 points sur 20 gratuit si on le fait bien!
Dexter — 16:03
Je l'ai repasser un peu, peut être ca marchere, il y a pas de commentaire par contre j'ai enlever
USE group7


CREATE OR REPLACE VIEW view_horaires_details AS
SELECT 
    h.HEURE_ARRIVEE,
Mehr anzeigen
message.txt
3 kB
sinon fait docker compose up ( sans le -d ) et tu verra un vlog avec des warning et des truc comme ca
Couata — 16:09
okok
Dexter — 16:33
attention il y a un prbleme , fait bien attention que tout les instances de HORRAIRES on bien deux RR, sinon rien ne marche
je crois que dans les vues il y en a un qui n'a que un R
Couata — 16:34
oh ptn de merde
ah ben ça na pas changé
Dexter — 16:39
pour les triggers ajoute un .ignore, il marche pas tous
faut que je revisite
04_triggers_group07DB.sql.ignore comme ca
ok j'ai réparer
je te donne la base de donnée complete sans faute:
Dexter — 16:48
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

Mehr anzeigen
01_create_group07DB.sql
3 kB
USE group7;

/* Script to load all files into the group07DB */

LOAD DATA INFILE '/docker-entrypoint-initdb.d/AGENCE.CSV'
INTO TABLE AGENCE
Mehr anzeigen
02_insert_group07DB.sql
2 kB
USE group7


CREATE OR REPLACE VIEW view_horaires_details AS
SELECT 
    h.HEURE_ARRIVEE,
Mehr anzeigen
03_view_group07DB.sql
3 kB
USE group7

DELIMITER$$

CREATE TRIGGER check_coords_belgium_update
BEFORE UPDATE ON ARRET
Mehr anzeigen
04_triggers_group07DB.sql
2 kB
avec ca tout fonctionne chez moi:

mais bon j'ai pas ta page3 nonp lus
Couata — 16:52
ma page 3 est assez simple
<?php
    include '../db_connection.php';
?>
<!DOCTYPE html>
<html>
    
Mehr anzeigen
message.txt
3 kB
Dexter — 16:54
je vais tester
Couata — 16:54
okok
Dexter — 16:55
ok la page load
je suis dedans
ou vient l'erreur maintenant?
ah ok quand tu click sur le button, je vois
Couata — 16:57
ben ça dit toujours au même endroit
au moment d'exécuter la commande sql
Dexter — 17:00
mmm, c'est les vues qui sont mal fait
je crois que la vue récursive fait des conenries et block tout
Couata — 17:02
c'est surement ça, mais jene vois pas où du coup
Dexter — 17:03
correction:
-- View for exceptions
CREATE OR REPLACE VIEW view_exceptions_details AS
SELECT 
    ej.CODE,
    s.NOM AS NOM_SERVICE,
    i.NOM AS NOM_ITINERAIRE,
    s.DATE_DEBUT,
    s.DATE_FIN
FROM EXCEPTION_JOUR ej
JOIN SERVICE s ON ej.SERVICE_ID = s.ID
JOIN TRAJET t ON s.ID = t.SERVICE_ID
JOIN ITINERAIRE i ON t.ITINERAIRE_ID = i.ID;

-- View for expanded services per date
CREATE OR REPLACE VIEW dates_services AS
WITH RECURSIVE dates_services_temp AS (
    SELECT s.ID AS service_id, s.NOM AS nom_service, s.DATE_DEBUT AS date_actuelle, s.DATE_FIN AS date_fin
    FROM SERVICE s
    UNION ALL
    SELECT dst.service_id, dst.nom_service, DATE_ADD(dst.date_actuelle, INTERVAL 1 DAY), dst.date_fin
    FROM dates_services_temp dst
    WHERE dst.date_actuelle < dst.date_fin
)
SELECT dst.service_id, dst.nom_service, dst.date_actuelle
FROM dates_services_temp dst
JOIN SERVICE s ON s.ID = dst.service_id
WHERE 
    (DAYOFWEEK(dst.date_actuelle) = 1 AND s.DIMANCHE = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 2 AND s.LUNDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 3 AND s.MARDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 4 AND s.MERCREDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 5 AND s.JEUDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 6 AND s.VENDREDI = 1) OR
    (DAYOFWEEK(dst.date_actuelle) = 7 AND s.SAMEDI = 1);
mais c'est chat , je sais pas si c'est correct ou comment ca marche
je vais tester
l'erreur a disparu
ici les fichiers de nouveau , ou juste pull le git
Je crois qu'on a fini apres ca
Couata — 17:09
et tu as recu les pages de jéromes?
Dexter — 17:09
Oui
on dirait que ca marche
Couata — 17:10
met les sur git
Dexter — 17:10
déja fait Xd
je sais pas si c'est correct ce qu'il a fait, mais je crois que oui, je lui fais confiance
Couata — 17:20
moi j'ait tjr le probleme
en ai-yant clone le git
Dexter — 17:23
pas de problème chez moi
Couata — 17:24
<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page1_functions.php';
    ?>
Mehr anzeigen
message.txt
6 kB
ici j'ai une nouvelle page1
qui est un peu plus beau
Couata — 17:25
après si ça ne marche pas que chez moi c'est bon
Dexter — 17:25
est ce que tu as des fucntions dans page2 et page3 que tu pourrais séparer?
﻿
Couata
couata
 
Kwatta meilleur que le nutella
<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page1_functions.php';
    ?>
    <head>
        <title>Page1</title>
        <link rel="stylesheet" type="text/css" href="../css/pages_style.css">
    </head>
    <body>
        <h1>Recherche sur l'agence, horaire et recherche exception</h1>
        <a href="../index.html">
            <div class="subbox">Revenir à l'acceuil</div>
        </a>
        <h2> Recherche Agence</h2>
        <div style="display: flex; gap: 20px; align-items: flex-end;">  
            <li>
                <form  method="get">
                    <label for="nom">Nom: </label>
                    <input type="text" id="nom_ag" name="nom_agence" placeholder="Entré le nom de L'agence" required>
                    <input type="submit" value="Submit">
                </form>
            </li>
            <li>
                <form  method="get">
                    <label for="nom">Telephone: </label>
                    <input type="text" id="tel_ag" name="tel_agence" placeholder="Entré le numéro de téléphone de L'agence" required>
                    <input type="submit" value="Submit">
                </form>
            </li>
        </div>

        <div id = 'message'>
            <?php
                if (isset($_GET['nom_agence'])) {
                    getDataAgence($pdo, 'nom_agence', 'AGENCE.NOM');
                } elseif (isset($_GET['tel_agence'])){
                    getDataAgence($pdo, 'tel_agence', 'TELEPHONE');
                }
            ?>
        </div>

        <h2> Recherche Horaire</h2>
        <div style="display: flex; gap: 20px; align: flex-end;">
            <li> 
                <form method="get">
                    <label for="nom">Nom arret: </label>
                    <input type="text" id="nom_ar" name="nom_arret" placeholder="Nom de l'arrêt" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Nom Itinéraire: </label>
                    <input type="text" id="nom_traj" name="nom_itineraire" placeholder="Nom de l'itineraire" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Heure d'arrivé: </label>
                    <input type="time" id="h_ariv" name="heure_arrive" placeholder="Heure d'arrivé" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Heure de départ: </label>
                    <input type="time" id="h_dep" name="heure_depart" placeholder="Heure de départ" required> 
                    <input type="submit" value="Submit">
                </form>
            </li>
            
        </div>

        <div id='message'>
            <?php
            if (isset($_GET['nom_arret'])) {
                    getDataHoraire($pdo, 'nom_arret', 'ARRET.NOM');
                } elseif (isset($_GET['nom_itineraire'])) {
                    getDataHoraire($pdo, 'nom_itineraire', 'ITINERAIRE.NOM');
                } elseif (isset($_GET['heure_arrive'])){
                    getDataHoraireHeure($pdo, 'heure_arrive', 'HEURE_ARRIVEE');
                } elseif (isset($_GET['heure_depart'])){
                    getDataHoraireHeure($pdo, 'heure_depart', 'HEURE_DEPART');
                }
            ?>
        </div>

        <h2>Recherche Exception</h2>
        <div style="display: flex; gap: 20px; align: flex-end;">
            <li> 
                <form method="get">
                    <label for="nom">Nom de l'itiniéraire: </label>
                    <input type="text" id="it_ser" name="itineraire_exception" placeholder="Nom de l'itinéraire" required>
                    <input type="submit" value="Submit">
                </form>
            </li>

            

            <li> 
                <form method="get">
                    <label for="nom">Nom du service: </label>
                    <input type="text" id="ex_ser" name="service_exception" placeholder="Nom du service" required>
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Date de début de l'exception: </label>
                    <input type="date" id="da_ser" name="date_exception_debut" placeholder="Date de début d'exception" required>
                    <input type="submit" value="Submit">
                </form>
            </li>

            <li> 
                <form method="get">
                    <label for="nom">Date de fin de l'exception: </label>
                    <input type="date" id="da_ser" name="date_exception_fin" placeholder="Date de fin d'exception" required>
                    <input type="submit" value="Submit">
                </form>
            </li>
        </div>
        <div id='message'>
        <?php
            if(isset($_GET['itineraire_exception'])){
                getDataException($pdo, 'itineraire_exception', 'ITINERAIRE.NOM');
            } elseif (isset($_GET['service_exception'])){
                getDataException($pdo, 'service_exception', 'SERVICE.NOM');
            } elseif (isset($_GET['date_exception_debut'])){
                getDataExceptionDate($pdo, 'date_exception_debut', 'DATE_DEBUT');
            } elseif(isset($_GET['date_exception_fin'])){
                getDataExceptionDate($pdo, 'date_exception_fin', 'DATE_FIN');
            }
        ?>
        </div>
        
    </body>
</html>
