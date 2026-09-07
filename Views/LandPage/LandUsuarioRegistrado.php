<?php
include '../../config/conexion.php';
session_start();

if (!isset($_SESSION['codUsuario'])) {
    header('Location: ../FlujoSesion/login.php');
    exit;
}

$tipo = $_SESSION['tipoUsuario'];
include '../../Views/Header/header.php';
?>
<main>
    <?php if ($tipo === 'administrador'): ?>
        <h2>Panel de Administración</h2>
        <p>Gestioná aerolíneas, novedades, promociones y reportes.</p>
    <?php elseif ($tipo === 'ceo'): ?>
        <h2>Panel CEO</h2>
        <p>Gestioná vuelos y promociones de tu aerolínea.</p>
    <?php else: ?>
        <h2>Panel Usuario</h2>
        <p>Buscá vuelos, reservá y consultá tu historial.</p>
    <?php endif; ?>
</main>
<?php include '../../Views/Footer/footer.php'; ?>

