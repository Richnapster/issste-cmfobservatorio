<?php
$conexion = new mysqli("localhost", "root", "", "laboratorio");

// Validar el parámetro ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID de paciente no válido.");
}

// Obtener datos actuales del paciente
$sql = "SELECT * FROM pacientes WHERE id = $id";
$resultado = $conexion->query($sql);
$paciente = $resultado->fetch_assoc();

if (!$paciente) {
    die("Paciente no encontrado.");
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $afiliacion = $_POST['afiliacion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $ruta_pdf = $paciente['ruta_pdf'];

    // Procesar PDF si se sube uno nuevo
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $paciente['expediente'] . ".pdf";
        $rutaDestino = "pdfs/" . $nombreArchivo;

        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $rutaDestino)) {
            $ruta_pdf = $rutaDestino;
        }
    }

    // Actualizar en la base de datos
    $stmt = $conexion->prepare("UPDATE pacientes SET nombre=?, afiliacion=?, fecha_nacimiento=?, ruta_pdf=? WHERE id=?");
    $stmt->bind_param("ssssi", $nombre, $afiliacion, $fecha_nacimiento, $ruta_pdf, $id);
    $stmt->execute();

    header("Location: editar.php?id=$id&guardado=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            margin-top: 20px;
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .alerta-exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            max-width: 500px;
        }

        .pdf-link {
            margin-top: 10px;
            font-size: 14px;
        }

        .pdf-link a {
            color: #27ae60;
            text-decoration: none;
        }

        .pdf-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php if (isset($_GET['guardado']) && $_GET['guardado'] == 1): ?>
        <div class="alerta-exito">
            ✅ Cambios guardados correctamente.
        </div>
    <?php endif; ?>

    <h2>Editar Paciente</h2>

    <form method="post" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($paciente['nombre']) ?>">

        <label>Afiliación:</label>
        <input type="text" name="afiliacion" value="<?= htmlspecialchars($paciente['afiliacion']) ?>">

        <label>Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($paciente['fecha_nacimiento']) ?>">

        <label>Subir expediente PDF:</label>
        <input type="file" name="pdf">

        <?php if (!empty($paciente['ruta_pdf'])): ?>
            <div class="pdf-link">
                Documento actual: <a href="<?= htmlspecialchars($paciente['ruta_pdf']) ?>" target="_blank">Ver PDF</a>
            </div>
        <?php endif; ?>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>