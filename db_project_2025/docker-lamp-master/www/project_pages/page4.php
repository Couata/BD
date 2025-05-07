<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page4functions.php';
    ?>
    <head>
        <title>Page4</title>
        <link rel="stylesheet" type="text/css" href="../css/pages_style.css">
    </head>
    <body>
        <h1>Page 4: Affichage temps d'arret moyen</h1>
        <a href="../index.html">
            <div class="subbox">Revenir à l'acceuil</div>
    </a>
        <table>
    <tr><td> Nom Itinéraire </td> <td> Nom Trajet </td> <td> AVG_STOP_TIME </td> </tr>
    <?php
    $tableau = getAllTrajetAvgStopTime($pdo);
    $i=1;
    $name="";
    foreach ($tableau as $l): 
        if($name!=$l['NOM'])
            $i=1;
        else if($l['TRAJET_ID']==NULL)
            $i=""
    ?>
        <tr>
            <td><?= $name=$l['NOM'] ?></td>
            <td><?= $i ?></td>
            <td><?= $l['AVG_TIME_STOP'] ?></td>
        </tr>
            
    <?php $i++;
    endforeach; 
    ?>
</table>
    </body>
</html>
