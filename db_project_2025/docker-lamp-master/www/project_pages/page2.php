<!DOCTYPE html>
<html>
    <?php
   include '../db_connection.php';
    ?>
    <head>
        <title>BD Project - Question 2</title>
    </head>
    <body>
        <h1>Insertion de nouveau service</h1>
        <!-- Insertion de code PHP -->
        <form method="post"> 

            <label>Nom du service</label>
            <input type="text" name="nom_service" placeholder="Donnez le nom du service" required> <br> <!-- Case nom service -->


            <label>Jours de la semaine :</label><br> <!-- Cases jours -->
            <input type="checkbox" name="jours[]" value="Lundi"> Lundi<br>
            <input type="checkbox" name="jours[]" value="Mardi"> Mardi<br>
            <input type="checkbox" name="jours[]" value="Mercredi"> Mercredi<br>
            <input type="checkbox" name="jours[]" value="Jeudi"> Jeudi<br>
            <input type="checkbox" name="jours[]" value="Vendredi"> Vendredi<br>
            <input type="checkbox" name="jours[]" value="Samedi"> Samedi<br>
            <input type="checkbox" name="jours[]" value="Dimanche"> Dimanche<br>

            <label for="date_debut"> Date de début :</label> <!-- date début -->
            <input type="date" name="date_debut" placeholder="Date de début" required><br>

            <label for="date_debut"> Date de fin :</label> <!-- date fin-->
            <input type="date" name="date_fin" placeholder="Date de fin" required><br>

            <textarea rows="5" name="exception_service"></textarea> <br> <!--exception -->

            <input type="submit" value="Ajouter le nouveau service"> <!-- Bouton submit -->
        </form>

        <?php
        // -----------------------------------------------------
        //          affiche ce qu'on met
        // -----------------------------------------------------

        echo "<ul>";
        if(isset($_POST['nom_service'])){
            $nom = $_POST['nom_service'];
            echo "$nom <br>";
        }

        if(isset($_POST['exception_service'])){
            echo "{$_POST['exception_service']}";
            $lines = explode("\n", $_POST['exception_service']);
            if($lines){ 
                foreach ($lines as $line) { 
                    // Trim whitespaces, empty strings are considered false 
                    $x = trim($line); 
                    if ($x) echo '<li>'. $x .'</li>'; 
                }
            }
        }
        if (isset($_POST['jours'])) {
            $joursCoches = $_POST['jours']; // C'est un tableau des jours cochés
            foreach ($joursCoches as $jour) {
                echo "Jour coché : $jour<br>";
            }
        } else {
            echo "Aucun jour n'a été coché.";
        }
        echo '</ul>';

        // -----------------------------------------------------
        //             Analyse de ce qu'on met
        // -----------------------------------------------------

        // Récupérer les données du formulaire
        $service_valide = true;
        $exception_valide = true;

        $nom_service = $_POST['nom_service'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $date_actuel = date("Y-m-d");

        $exception = $_POST['exception_service'];
        $lines = explode("\n", $exception);

        echo "<ul>";
        // vérifier les inputs du nouveau service
        if(isset($_POST['jours'])){
            $jours = $_POST['jours'];
            // Convertir les jours sélectionnés en valeurs booléennes (0 ou 1)
            $lundi = in_array('Lundi', $jours) ? 1 : 0;
            $mardi = in_array('Mardi', $jours) ? 1 : 0;
            $mercredi = in_array('Mercredi', $jours) ? 1 : 0;
            $jeudi = in_array('Jeudi', $jours) ? 1 : 0;
            $vendredi = in_array('Vendredi', $jours) ? 1 : 0;
            $samedi = in_array('Samedi', $jours) ? 1 : 0;
            $dimanche = in_array('Dimanche', $jours) ? 1 : 0;
        } else{
            $service_valide = false;
            echo "Il est nécessaire de coché au moins un jour <br>";
        }
        if($date_debut > $date_fin){
            $service_valide = false;
            echo "La date de fin ne doit pas être avant la date de début <br> ";
        }
        if($date_debut < $date_actuel){
            echo "La date de début du service ne peut pas être dans le passé ($date_actuel) <br>";
        }
        echo "</ul>";

        if($service_valide){
            $sql = "INSERT INTO SERVICE (NOM, LUNDI, MARDI, MERCREDI, JEUDI, VENDREDI, SAMEDI, DIMANCHE, DATE_DEBUT, DATE_FIN)
            VALUES (:nom, :lundi, :mardi, :mercredi, :jeudi, :vendredi, :samedi, :dimanche, :date_debut, :date_fin)";

            $stmt = $pdo->prepare($sql);
            /*
            $stmt->execute([
                ':nom' => $nom_service,
                ':lundi' => $lundi,
                ':mardi' => $mardi,
                ':mercredi' => $mercredi,
                ':jeudi' => $jeudi,
                ':vendredi' => $vendredi,
                ':samedi' => $samedi,
                ':dimanche' => $dimanche,
                ':date_debut' => $date_debut,
                ':date_fin' => $date_fin
            ]);
            */
        }

        // -----------------------------------------------------
        //      Séparation des exception et vérification
        // -----------------------------------------------------

        $parsed_exception = [];
        foreach ($lines as $line) {
            // Diviser la ligne en deux parties : la date et l'exception
            $parts = explode(" ", trim($line));
            // Vérifier que la ligne a bien été divisée en deux
            if (count($parts) == 2) {
                $date = $parts[0];  // La date
                if($parts[1] == "INCLUS"){
                    $exception = 1;
                } elseif ($parts[1] == "EXCLUS"){
                    $exception = 2;
                } else{
                    $exception_valide = false;
                }
                // Ajouter les données dans le tableau
                $parsed_exceptions[] = [
                    'date' => $date,
                    'exception' => $exception
                ];
            } else {
                $exception_valide = false;
            }
        }

        if($exception_valide){
            echo "<ul> vrai</ul>";


            
        } else {
            echo "<ul> Une des exception est fausse</ul>";
        }
        ?>
    </body>
</html>