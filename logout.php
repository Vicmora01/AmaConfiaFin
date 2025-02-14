<?php
session_start();
session_destroy(); // Cerrar sesión
header("Location: pages-login.html");
exit();
?>
