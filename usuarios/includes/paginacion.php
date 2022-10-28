<?php
$consultaTotal = $conexionBdPrincipal->query($SQL);
$numTotal = $consultaTotal->num_rows;
$limite = $configu['conf_paginacion'];

$dpto =  "";
if(isset($_GET["dpto"])){
	$dpto =  $_GET["dpto"];
}

$busqueda =  "";
if(isset($_GET["busqueda"])){
	$busqueda =  $_GET["busqueda"];
}

$q =  "";
if(isset($_GET["q"])){
	$q =  $_GET["q"];
}

$cte =  "";
if(isset($_GET["cte"])){
	$cte =  $_GET["cte"];
}

if(!isset($_GET["inicio"]) or !is_numeric($_GET["inicio"]))
	$inicio = 0;
else{
	if($_GET["inicio"]==1)
		$inicio = 0;
	else
		$inicio = ($_GET["inicio"] - 1) * $limite;	
}	
$paginas = ceil($numTotal/$limite);
?>
<!--<div class="pagination pagination-right">-->
<div class="pagination">
    <ul>
		<?php
        if(isset($_GET["inicio"]) and $_GET["inicio"]>1 and $paginas>1)
        	echo '<li><a href="'.$_SERVER['PHP_SELF'].'?inicio='.($_GET["inicio"]-1).'&dpto='.$dpto.'&busqueda='.$busqueda.'&q='.$q.'&cte='.$cte.'">Anterior</a></li>';
        else
        	echo '<li class="disabled"><a href="#">Anterior</a></li>';
        for($i=1; $i<=$paginas; $i++){
			if(isset($_GET["inicio"]) and $i==$_GET["inicio"])
				echo '<li class="active"><a href="#">'.$i.'</a></li>';
			else
				echo '<li><a href="'.$_SERVER['PHP_SELF'].'?inicio='.$i.'&dpto='.$dpto.'&busqueda='.$busqueda.'&q='.$q.'&cte='.$cte.'">'.$i.'</a></li>';
        }
        if(isset($_GET["inicio"]) and $_GET["inicio"]<$paginas)
        	echo '<li><a href="'.$_SERVER['PHP_SELF'].'?inicio='.($_GET["inicio"]+1).'&dpto='.$dpto.'&busqueda='.$busqueda.'&q='.$q.'&cte='.$cte.'">Siguiente</a></li>';
        else
        	echo '<li class="disabled"><a href="#">Siguiente</a></li>';
        ?>
    </ul>
</div>