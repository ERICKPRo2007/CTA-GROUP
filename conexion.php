<?php
// ── Configuración de la base de datos ──────────────────────────
$host     = "localhost";       // Servidor (no cambiar en XAMPP)
$usuario  = "root";            // Usuario MySQL por defecto en XAMPP
$password = "";                // Contraseña vacía por defecto en XAMPP
$base     = "mi_sistema";      // Nombre de tu base de datos

// ── Crear conexión ─────────────────────────────────────────────
$conexion = mysqli_connect($host, $usuario, $password, $base);

// ── Verificar conexión ─────────────────────────────────────────
if (!$conexion) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}
?>
