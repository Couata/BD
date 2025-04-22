<!DOCTYPE html>
<html>
    <!-- Connexion a la base de données -->
    <?php
    include '../db_connection.php';
    ?>
    <head>
        <title>Départements</title>
    </head>
    <body>
        <h1>Départements</h1>
        <?php
        /*$req contient les tuples de la requête*/
        $req = $bdd->query('SELECT * FROM AGENCE');
        /*On affiche tous les résultats de la requête*/
        while ($tuple = $req->fetch()) {
            echo "<p>" . $tuple['ID'] . " " . $tuple['NOM'] . "</p>";
        }
        ?>
    </body>
</html>