<?php
session_start();
include "conexion.php";

// Solo procesar si llega un POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ── Recoger y limpiar datos del formulario ─────────────────
    $usuario    = trim($_POST["usuario"]);
    $contrasena = trim($_POST["contrasena"]);

    // ── Validar que no estén vacíos ────────────────────────────
    if (empty($usuario) || empty($contrasena)) {
        header("Location: login.html?error=campos");
        exit();
    }

    // ── Consulta segura con prepared statements ────────────────
    $stmt = mysqli_prepare($conexion, "SELECT id, usuario FROM usuarios WHERE usuario = ? AND contrasena = MD5(?)");
    mysqli_stmt_bind_param($stmt, "ss", $usuario, $contrasena);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // ── Verificar si existe el usuario ─────────────────────────
    if (mysqli_num_rows($resultado) == 1) {
        $fila = mysqli_fetch_assoc($resultado);

        // Guardar sesión
        $_SESSION["id"]      = $fila["id"];
        $_SESSION["usuario"] = $fila["usuario"];

        // ✅ Login exitoso — redirige a tu página principal
        header("Location: dashboard.php");
        exit();

    } else {
        // ❌ Credenciales incorrectas
        header("Location: login.html?error=invalido");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
}
?>
