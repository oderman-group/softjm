<div class="row-fluid">
    <div class="span12">
        <div class="content-widgets light-gray">
            <div class="widget-head green">
                <h3>Cotización</h3>
            </div>
            <div class="widget-container">
                <table class="table table-striped table-bordered" id="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>TIPO</th>
                    <th>Fecha Propuesta</th>
                    <th>Productos</th>
                    <th>Responsable</th>
                    <th>Vendedor</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $consulta = $conexionBdPrincipal->query("SELECT * FROM cotizacion
                INNER JOIN clientes ON cli_id=cotiz_cliente AND cli_id='".$_GET["cte"]."'
                INNER JOIN usuarios ON usr_id=cotiz_creador
                WHERE cotiz_id_empresa='".$idEmpresa."'
                ");
                $no = 1;
                while($res = mysqli_fetch_array($consulta, MYSQLI_BOTH)){
                    $consultaVendedor = $conexionBdPrincipal->query("SELECT * FROM usuarios WHERE usr_id='".$res['cotiz_vendedor']."' AND usr_id_empresa='".$idEmpresa."'");
                    $vendedor = mysqli_fetch_array($consultaVendedor, MYSQLI_BOTH);
                    
                    $fondoCotiz = '';
                    if($res['cotiz_vendida']==1){
                        $fondoCotiz = 'aquamarine';
                    }

                    $tipoCotizacion = 'COTIZACIÓN';
                    if ($res['cotiz_es_precotizacion'] == 1) {
                        $tipoCotizacion = '<span style="background-color:yellow;">PRE-COTIZACIÓN</span>';
                    }
                ?>
                <tr>
                    <td style="background-color: <?=$fondoCotiz;?>;"><?=$res['cotiz_id'];?></td>
                    <td><?= $tipoCotizacion; ?></td>
                    <td><?=$res['cotiz_fecha_propuesta'];?></td>
                    <td>
                        <?php
                            $productos = $conexionBdPrincipal->query("SELECT * FROM cotizacion_productos
                            INNER JOIN productos ON prod_id=czpp_producto
                            WHERE czpp_cotizacion='".$res['cotiz_id']."'
                            ");
                            $i = 1;
                            while($prod = mysqli_fetch_array($productos, MYSQLI_BOTH)){
                                echo "<b>".$i.".</b> ".$prod['prod_nombre'].", ";
                                $i++;
                            }
                        ?>

                        <?php
                        $combos = $conexionBdPrincipal->query("SELECT combo_nombre FROM cotizacion_productos
                        INNER JOIN combos ON combo_id=czpp_combo
                        WHERE czpp_cotizacion='" . $res['cotiz_id'] . "' AND czpp_tipo=".CZPP_TIPO_COTZ."
                        ");
                                    $i = 1;
                                    while ($comb = mysqli_fetch_array($combos, MYSQLI_BOTH)) {
                                        if($i==1){echo "<br><b>Combos:</b><br>";}
                                        echo "<b>" . $i . ".</b> " . $comb['combo_nombre'] . ", ";
                                        $i++;
                                    }
                                    ?>

                    </td>
                    <td><?php if(isset($res['usr_nombre'])) echo strtoupper($res['usr_nombre']);?></td>
                    <td><?php if(isset($vendedor['usr_nombre'])) echo strtoupper($vendedor['usr_nombre']);?></td>
                    <td>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php if($_SESSION["id"]==$res['cotiz_creador'] or $_SESSION["id"]==$res['cotiz_vendedor'] or $datosUsuarioActual[3]==1){?>
                                <?php if (Modulos::validarRol([79], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                <li><a href="cotizaciones-editar.php?id=<?=$res['cotiz_id'];?>#productos"> Editar</a></li>
                                <?php } ?>

                                <!--<li><a href="sql.php?id=<?=$res['cotiz_id'];?>&get=22" onClick="if(!confirm('Desea eliminar el registro?')){return false;}">Eliminar</a></li>-->

                                <?php } //el codigo 22 no se encontro en el archivo sql?>
                                <?php if (Modulos::validarRol([50], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                <li><a href="reportes/formato-cotizacion-1_pdf.php?id=<?=$res['cotiz_id'];?>" target="_blank">Imprimir</a></li>
                                <?php } ?>
                                
                                <?php if (Modulos::validarRol([380], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion)) {?>
                                <li><a href="sql.php?get=46&id=<?=$res['cotiz_id'];?>" onClick="if(!confirm('Desea replicar este registro?')){return false;}">Replicar</a></li>
                                <?php } ?>		
                                <?php //el codigo 46 no se encontro en el archivo sql ?> 
                                <?php if (
                                    Modulos::validarRol([381], $conexionBdPrincipal, $conexionBdAdmin, $datosUsuarioActual, $configuracion) &&
                                    !empty($res['cotiz_ticket']) &&
                                    $res['cotiz_es_precotizacion'] != 1
                                    ) {?>
                                        <li><a href="bd_create/cotizaciones-generar-pedido.php?id=<?= $res['cotiz_id']; ?>" onClick="if(!confirm('Desea generar pedido de esta cotización?')){return false;}">Generar pedido</a></li>
                                <?php } ?>		
                                <?php //el codigo 48 no se encontro en el archivo sql ?> 
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php $no++;}?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>