<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page2_functions.php';
    ?>
    <head>
        <title>BD Project - Question 2</title>
        <link rel="stylesheet" type="text/css" href="../css/pages_style.css">
    </head>
    <body>
        <h1>Insertion de nouveau service</h1>
        <a href="../index.html">
            <div class="subbox">Revenir à l'acceuil</div>
        </a>

        <form method="post"> 
            <label>Nom du service</label>
            <input type="text" name="nom_service" placeholder="Donnez le nom du service" required><br>

            <label>Jours de la semaine :</label><br>
            <input type="checkbox" name="jours[]" value="Lundi"> Lundi<br>
            <input type="checkbox" name="jours[]" value="Mardi"> Mardi<br>
            <input type="checkbox" name="jours[]" value="Mercredi"> Mercredi<br>
            <input type="checkbox" name="jours[]" value="Jeudi"> Jeudi<br>
            <input type="checkbox" name="jours[]" value="Vendredi"> Vendredi<br>
            <input type="checkbox" name="jours[]" value="Samedi"> Samedi<br>
            <input type="checkbox" name="jours[]" value="Dimanche"> Dimanche<br>

            <label for="date_debut"> Date de début :</label>
            <input type="date" name="date_debut" required><br>

            <label for="date_fin"> Date de fin :</label>
            <input type="date" name="date_fin" required><br>

            <textarea rows="5" name="exception_service" placeholder="Exceptions (format: YYYY-MM-DD INCLUS/EXCLUS)"></textarea><br>

            <input type="submit" value="Ajouter le nouveau service">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            display_post_data($_POST);

            $nom_service = $_POST['nom_service'];
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $exception = $_POST['exception_service'];

            $service_valide = isset($_POST['jours']);
            if (!$service_valide) echo "Il est nécessaire de cocher au moins un jour <br>";

            $service_valide &= validate_dates($date_debut, $date_fin);

            $jours_flags = $service_valide ? get_day_flags($_POST['jours']) : [];
            extract($jours_flags);

            [$parsed_exceptions, $exception_valide] = parse_exceptions($exception);

            echo $exception_valide ? "<ul> vrai</ul>" : "<ul> Une des exceptions est fausse</ul>";

            if ($service_valide) {

                $lastId = get_last_service_id($pdo);
                if (is_int($lastId)) {
                    echo "TRUE";
                } else {
                    echo "FALSE";
                }
                var_dump($lastId);
            }
        }
        ?>
    </body>
</html>
