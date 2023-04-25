<?php
require_once("../sesion.php");

mysqli_query($conexionBdPrincipal,"UPDATE clientes_tikets SET tik_asunto_principal='" . mysqli_real_escape_string($conexionBdPrincipal,$_POST["asuntoP"]) . "', tik_tipo_tiket='" . $_POST["tipoS"] . "', tik_fecha_creacion='" . $_POST["fechaInicio"] . "', tik_estado='" . $_POST["estado"] . "', tik_prioridad='" . $_POST["prioridad"] . "', tik_referencia='" . $_POST["referencia"] . "', tik_canal='" . $_POST["canal"] . "', tik_equipo='" . $_POST["equipo"] . "', tik_etapa='" . $_POST["etapa"] . "', tik_tipo_negocio='" . $_POST["tipoNegocio"] . "', tik_origen_negocio='" . $_POST["origenNegocio"] . "', tik_valor='" . $_POST["valor"] . "', tik_razon_perdido='" . $_POST["razonPerdido"] . "', tik_razon_ganado='" . $_POST["razonGanado"] . "' WHERE tik_id='" . $_POST["id"] . "'");
	
echo '<script type="text/javascript">window.location.href="../clientes-tikets-editar.php?id=' . $_POST["id"] . '&msg=2&cte=' . $_POST["cte"] . '";</script>';
exit();