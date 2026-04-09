<?php
$conexion = new mysqli("localhost", "root", "Ti080824", "laboratorio");

$id = $_GET['id'];
$sql = "DELETE FROM pacientes WHERE id=$id";
$conexion->query($sql);

header("Location: buscar.html");
exit;