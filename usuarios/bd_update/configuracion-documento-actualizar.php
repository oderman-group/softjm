<?php
require_once("../sesion.php");

$idPagina = 326;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");

$consulta = $conexionBdAdmin->query("SELECT * FROM documentos_configuracion WHERE dconf_id_empresa='".$_SESSION["dataAdicional"]["id_empresa"]."' AND  dconf_id_documento ='" . $_POST["id"]."'");
$numData= mysqli_num_rows($consulta);
if($numData < 1 ){
    mysqli_query($conexionBdAdmin,"INSERT INTO documentos_configuracion
    (dconf_id_documento,dconf_id_empresa, dconf_estilo, dconf_estilo_letra, dconf_tamano_letra,dconf_ubicacion_logo)VALUES('" . $_POST['id'] . "','" . $_SESSION["dataAdicional"]["id_empresa"] . "','" . $_POST["estilo"] . "','" . $_POST["estiloLetra"] . "','" . $_POST["tamanoLetra"] . "','" . $_POST["ubicacion"] ."')");
   
}else{
    $conexionBdAdmin->query("UPDATE documentos_configuracion SET dconf_estilo='" . $_POST["estilo"] . "', dconf_estilo_letra='" . $_POST["estiloLetra"]."', dconf_tamano_letra='" . $_POST["tamanoLetra"] . "', dconf_ubicacion_logo='".$_POST["ubicacion"]."' WHERE dconf_id_documento ='" . $_POST["id"]."' AND dconf_id_empresa='". $_SESSION["dataAdicional"]["id_empresa"]. "'");
    echo $ubicacion_logo;
  
}
if ($_FILES['logo']['name'] != "") {
    $destino = RUTA_PROYECTO."/usuarios/files";
    $archivo = subirArchivosAlServidor($_FILES['logo'], 'logo', $destino);

    $conexionBdAdmin->query("UPDATE documentos_configuracion SET dconf_logo='" . $archivo . "' WHERE dconf_id_documento ='" . $_POST["id"]."' AND dconf_id_empresa='". $_SESSION["dataAdicional"]["id_empresa"]. "'");
   

}
    include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="../documentos.php";</script>';
    exit();