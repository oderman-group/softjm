<?php
require_once("../sesion.php");

$consultaZona=mysqli_query($conexionBdAdmin,"SELECT * FROM localidad_ciudades WHERE ciu_id='" . $_POST["ciudad"] . "'");
$zona = mysqli_fetch_array($consultaZona, MYSQLI_BOTH);

if (trim($_POST["usuario"]) != "") {
    $consultaClientesV=mysqli_query($conexionBdPrincipal,"SELECT * FROM clientes WHERE cli_usuario='" . trim($_POST["usuario"]) . "' AND cli_id_empresa='".$idEmpresa."'");
    $clienteV = mysqli_num_rows($consultaClientesV);

    if ($clienteV > 0) {
        echo "<div style='font-family:arial; text-align:center'>Ya existe un cliente con este n&uacute;mero de NIT. Verifique para que no lo registre nuevamente.<br><br>
        <a href='javascript:history.go(-1);'>[P&aacute;gina anterior]</a></span> | <a href='../clientes.php'>[Ir a clientes]</a></div>";
        exit();
    }
}

$pais=$_POST["pais"];

if(empty($_POST["pais"])){
    $pais="Colombia";
}

$ciudad=$_POST["ciudad"];
$city="";

if($_POST["pais"]!="Colombia"){
    $ciudad="1122";
    $city=$_POST["ciuExtra"];
}

$clave1 = generarClaves();
$clave2 = generarClaves();

$direccion = $_POST["op1"] . " " . $_POST["op2"] . " " . $_POST["op3"] . " # " . $_POST["op4"] . " " . $_POST["op5"] . " - " . $_POST["op6"] . " - " . $_POST["op7"];

require_once RUTA_PROYECTO . '/usuarios/includes/api-ofima-conexion.php';
if (ofimaIntegracionActiva($conexionBdPrincipal, (int) $idEmpresa)) {
    $tipoDoc = isset($_POST['tipoDocumento']) ? (int) $_POST['tipoDocumento'] : 0;
    $faltantes = [];
    if ($tipoDoc !== 2 && $tipoDoc !== 3) {
        $faltantes[] = 'tipo de documento';
    }
    if (trim($_POST['usuario'] ?? '') === '') {
        $faltantes[] = 'documento';
    }
    if (trim($_POST['nombre'] ?? '') === '') {
        $faltantes[] = 'nombre';
    }
    if (trim($_POST['email'] ?? '') === '') {
        $faltantes[] = 'email';
    }
    if (trim($_POST['telefono'] ?? '') === '') {
        $faltantes[] = 'teléfono';
    }
    if (trim($_POST['celular'] ?? '') === '') {
        $faltantes[] = 'celular';
    }
    if (trim($direccion) === '' || preg_match('/^\s*#\s*-\s*-\s*$/', trim($direccion))) {
        $faltantes[] = 'dirección';
    }
    if (empty($_POST['ciudad'])) {
        $faltantes[] = 'ciudad';
    }
    if (!empty($faltantes)) {
        echo "<div style='font-family:arial; text-align:center'>Faltan datos requeridos para Ofima: " . htmlspecialchars(implode(', ', $faltantes)) . ".<br><br>
        <a href='javascript:history.go(-1);'>[Página anterior]</a></div>";
        exit();
    }
}

$clienteInsitucional = 0;

if ($_POST["clienteInstitucional"] == 1) {
    $clienteInsitucional = 1;
}

mysqli_query($conexionBdPrincipal,"INSERT INTO clientes(cli_nombre, cli_referencia, cli_categoria, cli_email, cli_telefono, cli_ciudad, cli_usuario, cli_clave, cli_direccion, cli_zona, cli_fecha_registro, cli_fecha_ingreso, cli_nivel, cli_celular, cli_telefonos, cli_sigla, cli_responsable, cli_clave_documentos, cli_tipo_documento, cli_pais, cli_ciudad_extranjera, cli_id_empresa, cli_usuario_acceso, cli_institucional, cli_nombre_evento, cli_forma_creacion)VALUES('" . $_POST["nombre"] . "','" . $_POST["referencia"] . "','" . CLI_CATEGORIA_PROSPECTO . "','" . $_POST["email"] . "','" . $_POST["telefono"] . "','" . $_POST["ciudad"] . "','" . trim($_POST["usuario"]) . "','" . $clave1 . "','" . strtoupper($direccion) . "','" . $zona[2] . "',now(), NULL, 3, '" . $_POST["celular"] . "', '" . $_POST["telefonos"] . "','" . $_POST["sigla"] . "','" . $_SESSION["id"] . "','" . $clave2 . "','" . $_POST["tipoDocumento"] . "','" . $pais . "','" . $city . "','" . $idEmpresa . "','" . trim($_POST["usuarioAcceso"]) . "".$_POST["dominio"]."', '".$clienteInsitucional."','" . $_POST["nombreEvento"] . "', 'MANUAL')");
$idInsertU = mysqli_insert_id($conexionBdPrincipal);

if (!empty($_POST["grupos"])) {

    $numero   = (count($_POST["grupos"]));
    $contador = 0;

    mysqli_query($conexionBdPrincipal,"DELETE FROM clientes_categorias WHERE cpcat_cliente='" . $idInsertU . "'");

    while ($contador < $numero) {
        mysqli_query($conexionBdPrincipal,"INSERT INTO clientes_categorias(cpcat_cliente, cpcat_categoria)VALUES('" . $idInsertU . "'," . $_POST["grupos"][$contador] . ")");
        $contador++;
    }
}

//Crear automáticamente la sucursal
mysqli_query($conexionBdPrincipal,"INSERT INTO sucursales(sucu_cliente_principal, sucu_ciudad, sucu_direccion, sucu_telefono, sucu_celular, sucu_telefonos, sucu_nombre)VALUES('" . $idInsertU . "', '" . $_POST["ciudad"] . "', '" . $_POST["direccion"] . "', '" . $_POST["telefono"] . "', '" . $_POST["celular"] . "', '" . $_POST["telefonos"] . "','Sede principal')");


if ($_POST["contactoP"] == 1) {
    mysqli_query($conexionBdPrincipal,"INSERT INTO contactos(cont_nombre, cont_telefono, cont_email, cont_cliente_principal, cont_celular, cont_telefonos)VALUES('" . $_POST["nombre"] . "', '" . $_POST["telefono"] . "', '" . $_POST["email"] . "', '" . $idInsertU . "', '" . $_POST["celular"] . "','" . $_POST["telefonos"] . "')");
}

// Sincronizar con Ofima (Orion -> Ofima)
$ofimaQuery = '';
try {
    require_once RUTA_PROYECTO . '/usuarios/class/Cliente.php';
    $syncOfima = Cliente::sincronizarConOfima($idInsertU, $conexionBdPrincipal, $idEmpresa, 'CREATE');
    $tipoNotif = $syncOfima['notificacion']['tipo'] ?? (!empty($syncOfima['success']) ? 'success' : 'error');
    $msgNotif = $syncOfima['notificacion']['mensaje'] ?? ($syncOfima['error'] ?? $syncOfima['message'] ?? 'Resultado Ofima');
    if ($tipoNotif === 'success') {
        $ofimaQuery = '&ofima=ok&ofima_msg=' . urlencode($msgNotif);
    } elseif ($tipoNotif === 'warning') {
        $ofimaQuery = '&ofima=omitido&ofima_msg=' . urlencode($msgNotif);
    } else {
        $ofimaQuery = '&ofima=error&ofima_msg=' . urlencode($msgNotif);
    }
} catch (Exception $e) {
    error_log('Error al sincronizar cliente con Ofima: ' . $e->getMessage());
    $ofimaQuery = '&ofima=error&ofima_msg=' . urlencode('Ofima: ' . $e->getMessage());
}

echo '<script type="text/javascript">window.location.href="../clientes-editar.php?id=' . $idInsertU . '&msg=1' . $ofimaQuery . '";</script>';
exit();