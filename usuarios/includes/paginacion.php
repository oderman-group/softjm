<?php
$consultaTotal = $conexionBdPrincipal->query($SQL);
$numTotal = $consultaTotal->num_rows;
$limite = $configuracion['conf_paginacion'];

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
if($limite > 0) {
	$paginas = ceil($numTotal/$limite);
}	

?>
<!--<div class="pagination pagination-right">-->
<div class="pagination">
	<div style="text-align:center">
    <ul>
		<?php
        if(isset($_GET["inicio"]) and $_GET["inicio"]>1 and $paginas>1)
        	echo '<li><a href="'.$_SERVER['PHP_SELF'].'?inicio='.($_GET["inicio"]-1).'&dpto='.$dpto.'&busqueda='.$busqueda.'&q='.$q.'&cte='.$cte.'">Anterior</a></li>';
        else
        	echo '<li class="disabled"><a href="#">Anterior</a></li>';
        
		for ($i = 1; $i <= $paginas; $i++) {
			if ($i == 1 || $i == $paginas || ($i >= $_GET["inicio"] - 2 && $i <= $_GET["inicio"] + 2)) {
				if ($i == $_GET["inicio"]) {
	?>
		<li class="active" style="padding-left: 5px!important;">
			<a><?=$i?></a>
		</li>
	<?php       } else { ?>
		<li style="padding-left: 5px!important;">
			<a href="<?=$_SERVER['PHP_SELF']?>?inicio=<?=$i?>&dpto=<?=$dpto?>&busqueda=<?=$busqueda?>&q=<?=$q?>&cte=<?=$cte?>"><?=$i?></a>
		</li>
	<?php
				}
			} elseif (($i == 2 && $_GET["inicio"] > 3) || ($i == $paginas - 1 && $_GET["inicio"] < $paginas - 2)) {
	?>
		<li style="padding-left: 5px!important;">
			<span>...</span>
		</li>
	<?php
			}
		}


        if(isset($_GET["inicio"]) and $_GET["inicio"]<$paginas)
        	echo '<li><a href="'.$_SERVER['PHP_SELF'].'?inicio='.($_GET["inicio"]+1).'&dpto='.$dpto.'&busqueda='.$busqueda.'&q='.$q.'&cte='.$cte.'">Siguiente</a></li>';
        else
        	echo '<li class="disabled"><a href="#">Siguiente</a></li>';
        ?>
    </ul>
	</div>
</div>
