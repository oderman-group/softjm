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

$query = "SELECT m.mod_id, m.mod_nombre, me.mxe_id FROM modulos m 
LEFT JOIN modulos_empresa me ON me.mxe_id_modulo = m.mod_id and me.mxe_id_empresa = '".$_GET['clioId']."'
WHERE m.mod_padre = '".$_GET['modulo']."' or m.mod_id = '".$_GET['modulo']."';";

$result = $conexionBdAdmin->query($query);
if (mysqli_num_rows($result) > 0) {
    $no=1;
    while ($row = $result->fetch_assoc()) {
            $isChecked = $row['mxe_id'] ? "checked" : "";
?>
        <tr>
                <td><?= $row['mod_id'];?></td>
                <td><?= $row['mod_nombre']; ?></td>
                <td>
                    <input class="selectCheckbox check" type="checkbox" id="check-<?= $row['mod_id']; ?>" value="<?= $row['mod_id']; ?>" onchange="seleccionarPagina(this)" <?= $isChecked; ?>>
                    <span style="display: none;"> <?= $isChecked; ?> </span>
                </td>
        </tr>
        <script>
            // Obtener el elemento con el ID especificado
            var elementOption = document.getElementById('pag-<?= $row['mod_id']; ?>');
            var elementCheck = document.getElementById('check-<?= $row['mod_id']; ?>');

            // Verificar si el elemento se encontró
            if (elementOption) {
                //elementCheck.checked = true;
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