<?php include("usuarios/sesion.php");
$idPagina = 328;
include(RUTA_PROYECTO."/usuarios/includes/verificar-paginas.php");
?>
<?php
if ($_POST["p1"] == "" or $_POST["p2"] == "" or $_POST["p3"] == "" or $_POST["p4"] == "" or $_POST["p5"] == "") {
    ?>
            <span style='font-family:Arial; color:black; text-align:center;'>
                Debes esoger una opci&oacute;n para cada pregunta.<br>
                <a href="javascript:history.go(-1);">[Regresar]</a>
                </samp>
            <?php
            exit();
        }
        mysqli_query($conexionBdPrincipal,"UPDATE encuesta_satisfaccion SET encs_p1='" . $_POST["p1"] . "', encs_p2='" . $_POST["p2"] . "', encs_p3='" . $_POST["p3"] . "', encs_p4='" . $_POST["p4"] . "', encs_p5='" . $_POST["p5"] . "', encs_observaciones='" . $_POST["observaciones"] . "' WHERE encs_id='" . $_POST["id"] . "'");
        
            ?>
    
            <span style='font-family:Arial; color:black; text-align:center;'>MUCHAS GRACIAS POR TOMARSE EL TIEMPO PARA RESPONDER ESTA BREVE ENCUESTA.</samp>
                <script type="text/javascript">
                    function sacar() {
                        window.close();
                    }
                    setInterval('sacar()', 5000);
                </script>
            <?php
            exit();
?>