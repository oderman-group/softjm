<?php
include("../sesion.php");
?>
<table class="table table-striped table-bordered" id="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Seleccionar <input type="checkbox" id="all"> </th>
        </tr>
    </thead>
    <tbody>
<?php
$query = "SELECT p.pag_id, p.pag_nombre, p.pag_id_modulo, pp.pper_id FROM paginas p 
LEFT JOIN ".MAINBD.".paginas_perfiles pp ON p.pag_id = pp.pper_pagina AND pp.pper_tipo_usuario = '" . $_GET['tipoUsuario'] . "'
WHERE pag_id_modulo = '".$_GET['modulo']."'
";
$result = $conexionBdAdmin->query($query);
if (mysqli_num_rows($result) > 0) {
    $no=1;
    while ($row = $result->fetch_assoc()) {
            $isChecked = $row['pper_id'] ? "checked" : "";
?>
        <tr>
                <td><?= $row['pag_id'];?></td>
                <td><?= $row['pag_nombre']; ?></td>
                <td>
                    <input class="selectCheckbox check" type="checkbox" id="check-<?= $row['pag_id']; ?>" value="<?= $row['pag_id']; ?>" onchange="seleccionarPagina(this)" <?= $isChecked; ?>>
                    <span style="display: none;"> <?= $isChecked; ?> </span>
                </td>
        </tr>
        <script>
            // Obtener el elemento con el ID especificado
            var elementOption = document.getElementById('pag-<?= $row['pag_id']; ?>');
            var elementCheck = document.getElementById('check-<?= $row['pag_id']; ?>');

            // Verificar si el elemento se encontró
            if (elementOption) {
                elementCheck.checked = true;
            }
        </script>
<?php
        $no++;
    }
}
?>
    </tbody>
</table> 
<script>
    /**
     * Esta función es para marcar o desmarcar todas la paginas y tambien la agrega o elimina a la seleción
     */
	var checkboxes = document.querySelectorAll('.check');
    var marcarTodosCheckbox = document.getElementById('all');
    marcarTodosCheckbox.onchange = function() {
        var isChecked = this.checked;
        checkboxes.forEach(function(checkElement) {
            var page = checkElement.value;  
            if (isChecked) {
                if(checkElement.checked){
                    checkElement.checked = false;
                    eliminarPagina(page);
                }
                checkElement.checked = true;
                agregarPagina(page);
            } else {
                checkElement.checked = false;
                eliminarPagina(page);
            }
        });
    };
</script>
<?php 
exit();