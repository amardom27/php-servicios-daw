<?php

$url=DIR_API_HORARIO."/horarioProfesor/".$datos_usu_log["id_usuario"];
$respuesta=consumir_servicios_REST($url,"GET");
$json_horario=json_decode($respuesta,true);
if(!$json_horario)
{
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>".$url."</strong></p>"));
}

if(isset($json_horario["error"]))
{
    session_destroy();
    die(error_page("Examen Final PHP", "<h1>Examen Final PHP</h1><p>Error consumiendo el servicio REST <strong>".$json_horario["error"]."</strong></p>"));
}


foreach($json_horario["horario"] as $tupla)
{
    if(isset($horario_profe[$tupla["dia"]][$tupla["hora"]]))
    {
        $horario_profe[$tupla["dia"]][$tupla["hora"]]["grupo"].=" / ".$tupla["grupo"];
    }
    else
    {
        $horario_profe[$tupla["dia"]][$tupla["hora"]]["grupo"]=$tupla["grupo"];
        $horario_profe[$tupla["dia"]][$tupla["hora"]]["aula"]=$tupla["aula"];
    }
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen Final PHP</title>
    <style>
        .enlace{background: none;border:none;color:blue; text-decoration: underline;cursor:pointer}
        table,th,td{border:1px solid black}
        table{border-collapse: collapse; margin:0 auto; width:90%;text-align:center}
        th{background-color:#CCC}
    </style>
</head>
<body>
    <h1>Examen2 - PHP</h1>
    <form action="index.php" method="post">
        <p>Bienvenido <strong><?php echo $datos_usu_log["usuario"];?></strong> - <button type="submit" class="enlace" name="btnCerrarSesion" >Salir</button></p>
    </form>
    <h2>Su horario</h2>
    <h3>Horario del profesor: <?php echo $datos_usu_log["nombre"];?></h3>
    <table>
        <tr>
            <th></th>
            <?php
            for($i=1;$i<=count(DIAS);$i++)
                echo "<th>".DIAS[$i]."</th>";
            ?>
        </tr>
        <?php
        for($hora=1; $hora<=count(HORAS);$hora++)
        {
            echo "<tr>";
                echo "<th>".HORAS[$hora]."</th>";
                if($hora==4)
                {
                    echo "<td colspan='5'>RECREO</td>";
                }
                else
                {
                    for($dia=1;$dia<=count(DIAS);$dia++)
                    {
                        if(isset($horario_profe[$dia][$hora]))
                            echo "<td>".$horario_profe[$dia][$hora]["grupo"]."<br>(".$horario_profe[$dia][$hora]["aula"].")</td>";
                        else
                        echo "<td></td>";
                    }
                }
                
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>