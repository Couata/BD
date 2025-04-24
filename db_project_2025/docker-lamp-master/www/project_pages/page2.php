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
            <input type="text" name="nom_service" placeholder="Donnez le nom du service" required> <br>


            <label>Jours de la semaine :</label><br>
            <input type="checkbox" name="jours[]" value="Lundi"> Lundi<br>
            <input type="checkbox" name="jours[]" value="Mardi"> Mardi<br>
            <input type="checkbox" name="jours[]" value="Mercredi"> Mercredi<br>
            <input type="checkbox" name="jours[]" value="Jeudi"> Jeudi<br>
            <input type="checkbox" name="jours[]" value="Vendredi"> Vendredi<br>
            <input type="checkbox" name="jours[]" value="Samedi"> Samedi<br>
            <input type="checkbox" name="jours[]" value="Dimanche"> Dimanche<br>

            <label for="date_debut"> Date de début :</label>
            <input type="date" name="date_debut" placeholder="Date de début" required><br>

            <label for="date_debut"> Date de fin :</label>
            <input type="date" name="date_fin" placeholder="Date de fin" required><br>

            <textarea rows="5" name="exception_service"></textarea> <br> 


            <input type="submit" value="Ajouter le nouveau service">
        </form>

        <?php
        echo "<ul>";
        if(isset($_POST['nom_service'])){
            $nom = $_POST['nom_service'];
            echo "$nom ";
        }

        if(isset($_POST['exception_service'])){
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

        ?>
    </body>
</html>