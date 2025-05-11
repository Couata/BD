<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page5_functions.php';
    ?>
    <head>
        <title>Page5</title>
        <link rel="stylesheet" type="text/css" href="../css/pages_style.css">
    </head>

    <body>
        <h1>Page 5: Formulaire: Recherches noms de gares</h1>
        <a href="../index.html">
            <div class="subbox">Revenir à l'acceuil</div>
        </a>
        <form method="post" action="page5.php">
        <input type="text" name="gare" placeholder="Insérer (partie de) nom de gare" required>
        <input type="number" name="min_arret" min="0" placeholder="Minimum d'arrêts">
        <input type="submit" value="Soumettre">
    </form>
    <?php
    $res=NULL;
    if($_POST['min_arret']==NULL)
        $min_arret=0;
    else
        $min_arret=$_POST['min_arret'];
    if (isset($_POST['gare'])) {
           $gare=htmlentities(strtolower($_POST['gare']));
           $res=getArretsWith($pdo,$gare,$min_arret);
        }
    ?>
    <table>
    <?php
    echo "Recherche de gare contenant " . $gare . " ayant au minimum " . $min_arret . " arrêts de trains<br>";
    if($res==NULL)
        echo "\n aucun résultat trouvé";
    else
    {echo "<tr><td> Arret </td> <td> Service </td> <td> nb_arret </td> </tr>";
    foreach ($res as $l):
    ?>
        <tr>
            <td><?= $l['NOM_ARRET']?></td>
            <td><?= $l['NOM_SERVICE'] ?></td>
            <td><?= $l['NB_STOP'] ?></td>
        </tr>
            
    <?php
    endforeach;}
    ?>
    </table>
    </body>
</html>
