<?php include("usuarios/sesion.php");?>
<?php
$resultadoD = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM encuesta_satisfaccion
INNER JOIN clientes ON cli_id=encs_cliente
INNER JOIN usuarios ON usr_id=encs_atendido
WHERE encs_id='".$_GET["id"]."'"), MYSQLI_BOTH);
$producto = mysqli_fetch_array(mysqli_query($conexionBdPrincipal,"SELECT * FROM productos WHERE prod_id='".$resultadoD['encs_producto']."'"), MYSQLI_BOTH);
if($resultadoD[5]!=""){
?>
		<span style='font-family:Arial; color:red;'>Esta encuesta ya ha sido contestada. Redireccionando al inicio.</samp>
        <script type="text/javascript">
		function sacar(){
			window.location.href="index.php";
		} 
		setInterval('sacar()', 3000);
        </script>
<?php
	exit();
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>FROMATO DE ENCUESTA DE SATISFACCIÓN</title>
</head>
<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">

							<h1 style="text-align:center;">ENCUESTA DE SATISFACCIÓN</h1>
                            <form class="form-horizontal" method="post" action="sql.php">
                            <input type="hidden" name="idSql" value="1">
                            <input type="hidden" name="id" value="<?=$_GET["id"];?>">
                            <table width="90%" border="0" rules="groups" align="center">
							<tr align="center" style="height:30px; font-size:14px;">
                                <td align="right"><b>Fecha:</b></td> <td align="left"><?=$resultadoD['encs_fecha'];?></td>
                                <td align="right"><b>Cliente:</b></td> <td align="left"><?=$resultadoD['cli_nombre'];?></td>
                                <td align="right"><b>Funcionario:</b></td> <td align="left"><?=$resultadoD['usr_nombre'];?></td>
                                <td align="right"><b>Producto:</b></td> <td align="left"><?=$producto['prod_nombre'];?></td>
							</tr>
                            </table>
                            <p>&nbsp;</p>
                            <table width="90%" border="1" rules="all" align="center">
							<tr align="center" style="font-weight:bold; height:30px;">
                                <td align="left">Preguntas</td> 
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
							</tr>
                            <tr align="center">
                                <td align="left">¿El tiempo de atencion por parte del funcinario fue el adecuado?</td> 
                                <td><input type="radio" value="1" name="p1"></td>
                                <td><input type="radio" value="2" name="p1"></td>
                                <td><input type="radio" value="3" name="p1"></td>
                                <td><input type="radio" value="4" name="p1"></td>
                                <td><input type="radio" value="5" name="p1"></td>
							</tr>
                            <tr align="center">
                                <td align="left">¿El trato por parte del funcionario fue cordial?</td> 
                                <td><input type="radio" value="1" name="p2"></td>
                                <td><input type="radio" value="2" name="p2"></td>
                                <td><input type="radio" value="3" name="p2"></td>
                                <td><input type="radio" value="4" name="p2"></td>
                                <td><input type="radio" value="5" name="p2"></td>
							</tr>
                            <tr align="center">
                                <td align="left">¿Le fue resuelta su duda?</td> 
                                <td><input type="radio" value="1" name="p3"></td>
                                <td><input type="radio" value="2" name="p3"></td>
                                <td><input type="radio" value="3" name="p3"></td>
                                <td><input type="radio" value="4" name="p3"></td>
                                <td><input type="radio" value="5" name="p3"></td>
							</tr>
                            <tr align="center">
                                <td align="left">Califique de 1-5 la asesoria prestada, siendo 1 la calificación mas baja y 5 la mas alta</td> 
                                <td><input type="radio" value="1" name="p4"></td>
                                <td><input type="radio" value="2" name="p4"></td>
                                <td><input type="radio" value="3" name="p4"></td>
                                <td><input type="radio" value="4" name="p4"></td>
                                <td><input type="radio" value="5" name="p4"></td>
							</tr>
                            <tr align="center">
                                <td align="left">Califique de 1-5 la atención prestada, siendo 1 la calificación mas baja y 5 la mas alta</td> 
                                <td><input type="radio" value="1" name="p5"></td>
                                <td><input type="radio" value="2" name="p5"></td>
                                <td><input type="radio" value="3" name="p5"></td>
                                <td><input type="radio" value="4" name="p5"></td>
                                <td><input type="radio" value="5" name="p5"></td>
							</tr>
                            <tr align="center">
                                <td align="left">Coloque aquí las Observaciones</td> 
                                <td colspan="5"><textarea rows="3" cols="50" name="observaciones"></textarea></td>
							</tr>
							</table>
							<div align="center" style="margin-top:10px;"><button type="submit">ENVIAR RESULTADOS</button></div>
                            </form>

</body>
</html>