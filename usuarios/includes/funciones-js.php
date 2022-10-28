
<script type="application/javascript">
function notificaciones(){
	var usuario = <?=$_SESSION["id"];?>;
	var consulta = 1;
	  $('#notificaciones').empty().hide().html("...").show(1);
		datos = "usuario="+(usuario)+
				"&consulta="+(consulta);
		$.ajax({
		   type: "POST",
		   url: "ajax-notificaciones.php",
		   data: datos,
		   success: function(data){
			   $('#notificaciones').empty().hide().html(data).show(1);
		   }
		});
}
//setInterval('notificaciones()',200000);
window.onload = notificaciones();	
</script>	
