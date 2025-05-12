<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page1_functions.php';
    $service = getAllService($pdo);
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
                        <input type="text" id="nom_ag" name="nom_agence" placeholder="Entré le nom de L'agence">
                    <label for="nom">Telephone: </label>
                        <input type="text" id="tel_ag" name="tel_agence" placeholder="Entré le numéro de téléphone de L'agence">
                    <input type="submit" value="Submit">
                </form>
            </li>

        </div>

        <div id = 'message'>
            <?php
                if(isset($_GET['nom_agence']) || isset($_GET['tel_agence'])){
                    getAgenceSearch($pdo);
                }
            ?>
        </div>

        <h2> Recherche Horaire</h2>
        <div style="display: flex; gap: 20px; align: flex-end;">
            <li> 
                <form method="get">
                    <label for="nom">Nom arret: </label>
                        <input type="text" id="nom_ar" name="nom_arret" placeholder="Nom de l'arrêt">
                    <label for="nom">Nom Itinéraire: </label>
                        <input type="text" id="nom_traj" name="nom_itineraire" placeholder="Nom de l'itineraire">
                    <label for="nom">Heure d'arrivé: </label>
                        <input type="time" id="h_ariv" name="heure_arrive" placeholder="Heure d'arrivé">
                    <label for="nom">Heure de départ: </label>
                        <input type="time" id="h_dep" name="heure_depart" placeholder="Heure de départ"> 
                    <input type="submit" value="Submit">
                </form>
            </li>
        </div>

        <div>
            <?php
            if (isset($_GET['nom_arret']) || isset($_GET['nom_itineraire']) || isset($_GET['heure_arrive']) || isset($_GET['heure_depart'])) {
                    getHoraireSearch($pdo);
                }
            ?>
        </div>

        <h2>Recherche Exception</h2>
        <div style="display: flex; gap: 20px; align: flex-end;">
            <li> 
                <form method="get">
                    <label for="nom">Nom de l'itiniéraire: </label>
                        <input type="text" id="it_ser" name="itineraire_exception" placeholder="Nom de l'itinéraire">
                    <label for="nom">Nom du service: </label>
                    <select name="service_exception">
                        <option value="">-- Choisir -- </option>
                        <?php foreach ($service as $i):?>
                            <option value="<?= $i['NOM']?>"><?= htmlspecialchars($i['NOM']) ?></option>
                        <?php endforeach;?>
                    </select>
                        <!-- <input type="text" id="ex_ser" name="service_exception" placeholder="Nom du service"> -->
                    <label for="nom">Date de l'exception: </label>
                        <input type="date" id="da_ser" name="date_exception" placeholder="Date de l'exception">
                    <input type="submit" value="Submit">
                </form>
            </li>
        </div>
        <div>
        <?php
            if(isset($_GET['itineraire_exception']) || isset($_GET['service_exception']) || isset($_GET['date_exception'])){
                getExceptionSearch($pdo);
            }
        ?>
        </div>
        
    </body>
</html>