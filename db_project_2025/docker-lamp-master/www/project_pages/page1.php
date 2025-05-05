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
        <?php
            
            if (isset($_GET['nom_agence'])) {
                getDataAgence($pdo, 'nom_agence', 'NOM');
            } elseif (isset($_GET['tel_agence'])){
                getDataAgence($pdo, 'tel_agence', 'TELEPHONE');
            } elseif (isset($_GET['nom_arret'])) {
                getDataHoraire($pdo, 'nom_arret', 'ARRET.NOM');
            } elseif (isset($_GET['nom_itineraire'])) {
                getDataHoraire($pdo, 'nom_itineraire', 'ITINERAIRE.NOM');
            } elseif (isset($_GET['heure_arrive'])){
                getDataHoraireHeure($pdo, 'heure_arrive', 'HEURE_ARRIVEE');
            } elseif (isset($_GET['heure_depart'])){
                getDataHoraireHeure($pdo, 'heure_depart', 'HEURE_DEPART');
            } elseif(isset($_GET['itineraire_exception'])){
                getDataException($pdo, 'itineraire_exception', 'ITINERAIRE.NOM');
            } elseif (isset($_GET['service_exception'])){
                getDataException($pdo, 'service_exception', 'SERVICE.NOM');
            } elseif (isset($_GET['date_exception_debut'])){
                getDataExceptionDate($pdo, 'date_exception_debut', 'DATE_DEBUT');
            } elseif(isset($_GET['date_exception_fin'])){
                getDataExceptionDate($pdo, 'date_exception_fin', 'DATE_FIN');
            }
            else {
                echo "Veuillez entrer un critère de recherche.";
            }
        ?>
    </body>
</html>