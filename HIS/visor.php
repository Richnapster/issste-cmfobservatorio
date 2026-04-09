<?php
if (isset($_GET['archivo'])) {
    $archivo = $_GET['archivo'];
    if (file_exists($archivo)) {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Visor de PDF</title>
        </head>
        <body>
            <h2>Documento PDF</h2>
            <iframe src="<?= htmlspecialchars($archivo) ?>" width="100%" height="600px"></iframe>
        </body>
        </html>
        <?php
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "No se especificó ningún archivo.";
}
?>