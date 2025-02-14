<?php


session_start(); // Iniciar sesión

// Activar modo de depuración para ver errores (solo en desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conectar con la base de datos
$conexion = new mysqli("localhost", "root", "", "amaconfiafin");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$correo = trim($_POST['correo']);
$contrasenia = trim($_POST['contrasenia']);

// 🔹 **Validar que los campos no estén vacíos**
if (empty($correo) || empty($contrasenia)) {
    echo "<script>alert('Por favor, llena todos los campos'); window.location.href='pages-login.html';</script>";
    exit();
}

// Consultar el usuario en la base de datos
$sql = "SELECT id_usuario, nombre_completo, contrasenia, rol FROM Usuarios WHERE correo_electronico = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    
    // 🔹 **Verificar la contraseña cifrada correctamente**
    if (password_verify($contrasenia, $usuario['contrasenia'])) {
        // Almacenar datos en sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redirigir según el rol del usuario
        header("Location: index.html");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='pages-login.html';</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.location.href='pages-login.html';</script>";
}

// Cerrar conexiones
$stmt->close();
$conexion->close();
?>
