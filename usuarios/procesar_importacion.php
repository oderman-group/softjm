<?php
include("sesion.php");
header('Content-Type: application/json; charset=utf-8');

require '../librerias/Excel/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["error" => true, "mensaje" => "No se subió archivo válido."]);
        exit;
    }

    $fuente  = $conexionBdPrincipal->real_escape_string($_POST['fuente']);
    $asesor  = intval($_POST['asesor']);
    $usuario = intval($_SESSION["id"]); // Usuario que importa
    $ciudad  = $_POST['ciudad'];

    // Guardar archivo subido en carpeta "files/excel/"
    $nombreArchivo = time() . "_" . basename($_FILES['archivo']['name']);
    $rutaDestino   = "files/excel/" . $nombreArchivo;
    move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaDestino);

    // Insertar en tabla principal (prospectos_importacion)
    $conexionBdPrincipal->query("
        INSERT INTO prospectos_importacion
        (pi_nombre_archivo, pi_fuente, pi_asesor_encargado, pi_id_empresa, pi_created_by, pi_ciudad_evento)
        VALUES
        ('$nombreArchivo', '$fuente', $asesor, ".$idEmpresa.", $usuario, '$ciudad')
    ");
    $idArchivo = $conexionBdPrincipal->insert_id;

    // Leer Excel
    $spreadsheet = IOFactory::load($rutaDestino);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $insertados = 0;
    foreach ($rows as $i => $row) {
        if ($i === 0) continue; // Saltar cabecera

        $nombre   = $conexionBdPrincipal->real_escape_string(trim($row[0]));
        $telefono = $conexionBdPrincipal->real_escape_string(trim($row[1]));
        $email    = $conexionBdPrincipal->real_escape_string(trim($row[2]));

        if (empty($nombre)) continue;

        $conexionBdPrincipal->query("
            INSERT INTO prospectos_importacion_detalles
            (pid_id_archivo, pid_nombres, pid_email, pid_telefono, pid_estado, pid_notas)
            VALUES
            ($idArchivo, '$nombre', '$email', '$telefono', 'INICIAL', 'Importado ".date('Y-m-d')."')
        ");

        $insertados++;
    }

    //Borrar el archivo del servidor
    unlink($rutaDestino);

    $clientes = [];
    
    $consultaProspectos = $conexionBdPrincipal->query("SELECT *
    FROM prospectos_importacion_detalles
    INNER JOIN prospectos_importacion ON pi_id=pid_id_archivo AND (pi_asesor_encargado = '".$_SESSION["id"]."' || pi_created_by = '".$_SESSION["id"]."')
    ");

    while ($prospectos = mysqli_fetch_assoc($consultaProspectos)) {
        $clientes[] = [
            'id'             => $prospectos['pid_id'],
            'nombre_cliente' => $prospectos['pid_nombres'],
            'telefono'       => $prospectos['pid_telefono'],
            'email'          => $prospectos['pid_email'],
            'gestion_estado' => $prospectos['pid_estado'],
            'notas_previas'  => $prospectos['pid_notas'],
            'fuente'         => $referenciaLlegada[$prospectos['pi_fuente']],
            'fuente_id'      => $prospectos['pi_fuente'],
            'ciudad'         => $prospectos['pi_ciudad_evento'],
        ];
    }

    echo json_encode([
        "error"   => false,
        "mensaje" => "Importación finalizada. Se insertaron $insertados prospectos.",
        "archivo" => $idArchivo,
        "datos"   => $clientes
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => true, "mensaje" => "Error: ".$e->getMessage()]);
}
