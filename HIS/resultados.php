<?php
$conexion = new mysqli("localhost", "root", "", "laboratorio");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$busqueda = isset($_GET['busqueda']) ? $conexion->real_escape_string($_GET['busqueda']) : '';

$sql = "SELECT id, expediente, nombre, afiliacion, fecha_nacimiento, ruta_pdf 
        FROM pacientes 
        WHERE nombre LIKE '%$busqueda%' 
           OR expediente LIKE '%$busqueda%' 
           OR ruta_pdf LIKE '%$busqueda%'";


$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        a {
            color: #2980b9;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 800px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        iframe {
            width: 100%;
            height: 500px;
            border: none;
        }
        .button {
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin

    .button {
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    background


        }
    </style>
</head>
<body>
    <h2>Resultados de búsqueda</h2>
    <a href="buscar.html" class="button" style="background-color: #3498db; color: white; margin-bottom: 20px; display: inline-block;">🔍 Nueva búsqueda</a>



    <?php if ($resultado->num_rows > 0): ?>
        <table>
            <tr>
                <th>Expediente</th>
                <th>Nombre</th>
                <th>Afiliación</th>
                <th>Fecha de Nacimiento</th>
                <th>Acciones</th>
                <th>Documento</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['expediente']) ?></td>
                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                    <td><?= htmlspecialchars($fila['afiliacion']) ?></td>
                    <td><?= htmlspecialchars($fila['fecha_nacimiento']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $fila['id'] ?>">Editar</a> |
                        <a href="eliminar.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este paciente?')">Eliminar</a>
                    </td>
                    <td>
                        <?php
    $pdfPath = $fila['ruta_pdf'];
    if (!empty($pdfPath) && file_exists($pdfPath)) {
        echo "<a href='#' onclick=\"abrirModalPDF('$pdfPath')\">Ver PDF</a><br><small>$pdfPath</small>";
    } else {
        echo "Sin documento";
    }
    ?>


                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron resultados para "<?= htmlspecialchars($busqueda) ?>"</p>
    <?php endif; ?>

    <?php $conexion->close(); ?>

    <!-- Modal PDF Viewer -->
    <div id="pdfModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('pdfModal').style.display='none'">&times;</span>
            <iframe id="pdfFrame" src=""></iframe>
        </div>
    </div>

    <script>
        function abrirModalPDF(rutaPDF) {
            document.getElementById('pdfFrame').src = rutaPDF;
            document.getElementById('pdfModal').style.display = 'block';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('pdfModal');
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>