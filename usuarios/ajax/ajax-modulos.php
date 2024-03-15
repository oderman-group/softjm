<?php
include("../sesion.php");
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Seleccionar <input type="checkbox" id="all"> </th>
        </tr>
    </thead>
    <tbody>
<?php

$query = "SELECT m.mod_id, m.mod_nombre, m.mod_padre, me.mxe_id FROM modulos m 
LEFT JOIN modulos_empresa me ON me.mxe_id_modulo = m.mod_id AND me.mxe_id_empresa = '".$_GET['clioId']."'
WHERE m.mod_padre = '".$_GET['modulo']."' OR m.mod_id = '".$_GET['modulo']."';";

$result = $conexionBdAdmin->query($query);
if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
            $isChecked = $row['mxe_id'] ? "checked" : "";
?>
        <tr>
                <td><?= $row['mod_id'];?></td>
                <td style="<?= !empty($row['mod_padre']) ? "padding-left: 20px; font-size:12px; font-weight:bold;": "font-size:15px; font-weight:bold;";?>"><?= $row['mod_nombre']; ?></td>
                <td>
                    <input class="selectCheckbox check" type="checkbox" id="check-<?= $row['mod_id']; ?>" value="<?= $row['mod_id']; ?>" onchange="seleccionarModulo(this)" <?= $isChecked; ?>>
                    <span style="display: none;"> <?= $isChecked; ?> </span>
                </td>
        </tr>
        <?php
        if (!empty($row['mod_padre'])) {
            $querySubMod = "SELECT m.mod_id, m.mod_nombre, me.mxe_id FROM modulos m 
            LEFT JOIN modulos_empresa me ON me.mxe_id_modulo = m.mod_id AND me.mxe_id_empresa = '".$_GET['clioId']."'
            WHERE m.mod_padre = '".$row['mod_id']."' AND m.mod_id != '".$row['mod_id']."'";

            $resultSubMod = $conexionBdAdmin->query($querySubMod);
            if (mysqli_num_rows($resultSubMod) > 0) {
                while ($rowSubMod = $resultSubMod->fetch_assoc()) {
                        $isCheckedSubMod = $rowSubMod['mxe_id'] ? "checked" : "";
        ?>
            <tr>
                    <td><?= $rowSubMod['mod_id'];?></td>
                    <td style="padding-left: 40px;"><?= $rowSubMod['mod_nombre']; ?></td>
                    <td>
                        <input class="selectCheckbox check" type="checkbox" id="check-<?= $rowSubMod['mod_id']; ?>" value="<?= $rowSubMod['mod_id']; ?>" onchange="seleccionarModulo(this)" <?= $isCheckedSubMod; ?>>
                        <span style="display: none;"> <?= $isCheckedSubMod; ?> </span>
                    </td>
            </tr>
            <script>
                // Obtener el elemento con el ID especificado
                var elementOption = document.getElementById('pag-<?= $rowSubMod['mod_id']; ?>');
                var elementCheck = document.getElementById('check-<?= $rowSubMod['mod_id']; ?>');

                // Verificar si el elemento se encontró
                if (elementOption) {
                    //elementCheck.checked = true;
                }
            </script>
        <?php
                }
            }
        }
        ?>
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
    }
}
?>
    </tbody>
</table> 
<script>
    /**
     * Esta función es para marcar o desmarcar todas los Modulos y tambien la agrega o elimina a la seleción
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
                    eliminarModulo(page);
                }
                checkElement.checked = true;
                agregarModulo(page);
                } else {
                checkElement.checked = false;
                eliminarModulo(page);
            }
        });
    };
</script>
<?php 
exit();