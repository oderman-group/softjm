<?php
$dcto = 0;
$valorTotal = 0;

if($prod['czpp_valor']!=''){
	$valorTotal = $prod['czpp_valor'] * $prod['czpp_cantidad'];
}else{
	$valorTotal = 0 * $prod['czpp_cantidad'];
}


if($prod['czpp_cantidad']>0 and $prod['czpp_descuento']>0){
	$dcto = ($valorTotal * ($prod['czpp_descuento']/100));
	$totalDescuento = $totalDescuento+$dcto;	
}

$valorConDcto = $valorTotal - $dcto;
$totalIva =$totalIva + ($valorConDcto * ($prod['czpp_impuesto']/100));

$subtotal =$subtotal+$valorTotal;


$totalCantidad = $prod['czpp_cantidad'];

$fondo = 'white';
if ($no % 2 == 0) {
	$fondo = '#f8f9fa';
}