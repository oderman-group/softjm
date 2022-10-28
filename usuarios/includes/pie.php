<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
	<script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-app.js"></script>	
	<script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-database.js"></script>
	<script src="https://www.gstatic.com/firebasejs/6.2.0/firebase-auth.js"></script>	


	
	<script type="text/javascript" src="chat.js"></script>

<div class="copyright">
		<p>
			 &copy; 2017 - Todos los derechos reservados
		</p>
	</div>
	<div class="scroll-top">
		<a href="#" class="tip-top" title="Go Top"><i class="icon-double-angle-up"></i></a>
	</div>

<script type="text/javascript">
$(window).load(function() {
    $(".loader").fadeOut("slow");
});
</script>


<script type="application/javascript">

function axiosAjax(datos){
	//const axios = require('axios');

	// Make a request for a user with a given ID
	axios.get(datos.name)
	  .then(function (response) {
		// handle success
		console.log(response.data);
		document.getElementById("cargarAxios").innerHTML = response.data;
	  })
	  .catch(function (error) {
		// handle error
		console.log(error);
	  })
	  .then(function () {
		// always executed
	  });
}

	
</script>

<?php
include(RUTA_PROYECTO."/usuarios/includes/guardar-historial-acciones.php");

echo "se cargo el sitio web en ".$tiempoMostrar;
?>

<div style="height: 100px;"></div>