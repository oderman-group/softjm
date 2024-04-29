<?php
require_once("../sesion.php");

$sucursal = $_POST["sucursal"];
if ($_POST["sucursal"] == "") $sucursal = 0;

mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_tikets(tik_asunto_principal, tik_tipo_tiket, tik_fecha_creacion, tik_usuario_responsable, tik_estado, tik_cliente, tik_prioridad, tik_canal, tik_equipo, tik_referencia, tik_sucursal, tik_etapa, tik_tipo_negocio, tik_origen_negocio, tik_valor, tik_razon_perdido,  tik_razon_ganado, tik_observaciones)VALUES('" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asuntoP"]) . "','" . $_POST["tipoS"] . "','" . $_POST["fechaInicio"] . "','" . $_SESSION["id"] . "',1,'" . $_POST["cliente"] . "','" . $_POST["prioridad"] . "','" . $_POST["canal"] . "','" . $_POST["equipo"] . "','" . $_POST["referencia"] . "','" . $sucursal . "','" . $_POST["etapa"] . "','" . $_POST["tipoNegocio"] . "','" . $_POST["origenNegocio"] . "', '" . $_POST["valor"] . "', '" . $_POST["razonPerdido"] . "', '" . $_POST["razonGanado"] . "', '" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["observaciones"]) . "')");

$idInsertU = mysqli_insert_id($conexionBdPrincipal);
echo '<script type="text/javascript">window.location.href="../clientes-tikets-editar.php?id=' . $idInsertU . '&msg=1&cte=' . $_POST["cte"] . '";</script>';
exit();