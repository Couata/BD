<!DOCTYPE html>
<html>
    <?php
    include '../db_connection.php';
    include '../functions/page4_functions.php';
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
        <table border="1">
    <tr><td> Nom Itinéraire </td> <td> Nom Trajet </td> <td> AVG_STOP_TIME </td> </tr>
    <?php
    $tableau = getAllTrajetAvgStopTime($pdo);
    $i=1;
    $name="";
    foreach ($tableau as $l): 
        {if($name!=$l['NOM'])
            $i=1;    
        $name=$l['NOM'];
        if($l['TRAJET_ID']==NULL)
            $i="MOYENNE";
        if($l['TRAJET_ID']==NULL&&$l['NOM']==NULL)
            {
                $i="";
                $name="TEMPS MOYEN TOUS TRAJETS";
            }}
    ?>
        <tr>
            <td><?= $name?></td>
            <td><?= $i ?></td>
            <td><?= $l['AVG_TIME_STOP'] ?></td>
        </tr>
            
    <?php $i++;
    endforeach; 
    ?>
    </table>
    </body>
</html>
