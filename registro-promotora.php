<?php
// Iniciar sesión (opcional si deseas registrar quién realiza esta acción)
session_start();

// Conectar con la base de datos
$conexion = new mysqli("localhost", "root", "", "AmaConfiafin");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$nombre_completo = $_POST['nombre_completo'];
$edad = $_POST['edad'];
$genero = $_POST['genero'];
$correo_electronico = $_POST['correo_electronico'];
$telefono = $_POST['telefono'];

// Validar que no haya campos vacíos
if (empty($nombre_completo) || empty($edad) || empty($genero) || empty($correo_electronico) || empty($telefono)) {
    echo "<script>alert('Por favor, completa todos los campos.'); window.location.href='forms-cliente.html';</script>";
    exit();
}

// Verificar que el correo electrónico no esté registrado
$sql_check = "SELECT * FROM Promotora WHERE correo_electronico = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("s", $correo_electronico);
$stmt_check->execute();
$resultado_check = $stmt_check->get_result();

if ($resultado_check->num_rows > 0) {
    echo "<script>alert('El correo electrónico ya está registrado.'); window.location.href='forms-cliente.html';</script>";
    exit();
}

// Insertar la promotora en la base de datos
$sql_insert = "INSERT INTO Promotora (nombre_completo, edad, genero, correo_electronico, telefono) VALUES (?, ?, ?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);
$stmt_insert->bind_param("sisss", $nombre_completo, $edad, $genero, $correo_electronico, $telefono);

if ($stmt_insert->execute()) {
    echo "<script>alert('Promotora registrada exitosamente.'); window.location.href='forms-cliente.html';</script>";
} else {
    echo "<script>alert('Error al registrar la promotora.'); window.location.href='forms-cliente.html';</script>";
}

// Cerrar conexiones
$stmt_check->close();
$stmt_insert->close();
$conexion->close();
?>
