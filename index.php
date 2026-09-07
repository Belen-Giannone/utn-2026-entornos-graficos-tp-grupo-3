<?php
session_start();

// Si el usuario ya inició sesión → LandPage Registrado
if (isset($_SESSION['codUsuario'])) {
    header('Location: Views/LandPage/LandUsuarioRegistrado.php');
    exit;
} else {
    // Si no hay sesión → LandPage No Registrado
    header('Location: Views/LandPage/LandUsuarioNoRegistrado.php');
    exit;
}
?>

