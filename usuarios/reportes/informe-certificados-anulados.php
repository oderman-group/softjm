<?php
include("../sesion.php");

$configuracion = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM configuracion WHERE conf_id=1"));
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>INFORMES - CERTIFICADOS ANULADOS</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:10px;">

    <h1 style="text-align:center;">INFORMES</h1>
    <h2 style="text-align:center;">CERTIFICADOS ANULADOS</h2>
    <div align="center" style="margin-bottom:5px;"><img src="../files/<?= $configuracion['conf_logo']; ?>" height="100" alt="<?= $configuracion['conf_empresa']; ?>"></div>
    <table width="100%" border="1" rules="all" align="center">
        <thead>
            <tr style="height:30px;">
                <th>No</th>
                <th>ID</th>
                <th>Cliente</th>
                <th>Equipo</th>
                <th>Referencia</th>
                <th>Serial</th>
                <th>Sucursal</th>
                <th>Fecha Anulación</th>
                <th>Motivo Anulación</th>
                <th>Versión Actual</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $consulta = mysqli_query($conexionBdPrincipal,"SELECT * FROM remisiones
            INNER JOIN certificados_anulados ON certanu_id_certificado=rem_id
            LEFT JOIN clientes ON cli_id=rem_cliente
            LEFT JOIN usuarios ON usr_id=rem_asesor
            LEFT JOIN sucursales_propias ON sucp_id=usr_sucursal
            WHERE rem_id=rem_id 
            GROUP BY rem_id");

            $no = 1;
            while ($res = mysqli_fetch_array($consulta)) {
            ?>
                <tr>
                    <td align="center"><?= $no; ?></td>
                    <td align="center"><a href="../../v2.0/usuarios/empresa/lab-remisiones-editar.php?id=<?=$res['rem_id'];?>" target="_blank"><?=$res['rem_id'];?></a></td>
                    <td><a href="../clientes-editar.php?id=<?=$res['cli_id'];?>" target="_blank"><?=$res['cli_nombre'];?></a></td>
                    <td>
                        <?php if($res['rem_archivo']!=""){echo '<img src="../files/adjuntos/'.$res["rem_archivo"].'" width="20">';}?>
                        <?=$res['rem_equipo'];?>
                    </td>
                    <td><?=$res['rem_referencia'];?></td>
                    <td><?=$res['rem_serial'];?></td>
                    <td><?=$res['sucp_nombre'];?></td>
                    <td><?=$res['certanu_fecha_anulacion'];?></td>
                    <td><?=$res['certanu_descripcion'];?></td>
                    <td align="center"><a href="../../v2.0/usuarios/empresa/lab-certificado-imprimir.php?id=<?=$res['rem_id'];?>" target="_blank"><?="v".($res['certanu_version']+1);?></a></td>
                </tr>
            <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
</body>

</html>