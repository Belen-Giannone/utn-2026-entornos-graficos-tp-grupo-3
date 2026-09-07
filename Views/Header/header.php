<?php
$tipoUsuario = $_SESSION['tipoUsuario'] ?? null;
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../../styles.css">

<header class="site-header">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm" aria-label="Navegación principal">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand brand d-flex align-items-center gap-2" href="../../index.php">
      <span class="brand-mark" aria-hidden="true"><i class="bi bi-airplane-fill"></i></span>
      <span>ViajAR</span>
    </a>

    <!-- Botón hamburguesa para móvil -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menú dinámico -->
    <div class="collapse navbar-collapse" id="menuPrincipal">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="../../index.php"><i class="bi bi-house-door me-1" aria-hidden="true"></i>Inicio</a></li>

        <?php if ($tipoUsuario === 'administrador'): ?>
          <li class="nav-item"><a class="nav-link" href="../FuncionesAdmin/aerolineas-index.php"><i class="bi bi-buildings me-1" aria-hidden="true"></i>Aerolíneas</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesAdmin/auditoria-promociones.php"><i class="bi bi-ticket-perforated me-1" aria-hidden="true"></i>Promociones</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesAdmin/novedades-index.php"><i class="bi bi-megaphone me-1" aria-hidden="true"></i>Novedades</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesAdmin/global-reports.php"><i class="bi bi-bar-chart me-1" aria-hidden="true"></i>Reportes</a></li>

        <?php elseif ($tipoUsuario === 'ceo'): ?>
          <li class="nav-item"><a class="nav-link" href="../FuncionesCEO/vuelos-index.php"><i class="bi bi-airplane me-1" aria-hidden="true"></i>Vuelos</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesCEO/promociones-index.php"><i class="bi bi-ticket-perforated me-1" aria-hidden="true"></i>Promociones</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesCEO/reportes-ceo.php"><i class="bi bi-bar-chart me-1" aria-hidden="true"></i>Reportes</a></li>

        <?php elseif ($tipoUsuario === 'usuario'): ?>
          <li class="nav-item"><a class="nav-link" href="../FuncionesUsuario/buscar_vuelos.php"><i class="bi bi-search me-1" aria-hidden="true"></i>Buscar vuelos</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesUsuario/mis_reservas.php"><i class="bi bi-bookmark-check me-1" aria-hidden="true"></i>Mis reservas</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesUsuario/historial_compras.php"><i class="bi bi-receipt me-1" aria-hidden="true"></i>Historial</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesUsuario/novedades.php"><i class="bi bi-megaphone me-1" aria-hidden="true"></i>Novedades</a></li>
          <li class="nav-item"><a class="nav-link" href="../FuncionesUsuario/promociones.php"><i class="bi bi-stars me-1" aria-hidden="true"></i>Promociones</a></li>

        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="../LandPage/LandUsuarioNoRegistrado.php"><i class="bi bi-buildings me-1" aria-hidden="true"></i>Aerolíneas</a></li>
          <li class="nav-item"><a class="nav-link" href="../LandPage/LandUsuarioNoRegistrado.php#seccion-vuelos"><i class="bi bi-airplane me-1" aria-hidden="true"></i>Vuelos</a></li>
        <?php endif; ?>
      </ul>

      <!-- Botones de sesión -->
      <div class="user-actions d-flex">
        <?php if ($tipoUsuario): ?>
          <a href="../FuncionesUsuario/mi_perfil.php" class="btn btn-light btn-sm me-2"><i class="bi bi-person-circle me-1" aria-hidden="true"></i>Mi perfil</a>
          <a href="../FlujoSesion/logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>Salir</a>
        <?php else: ?>
          <a href="../FlujoSesion/login.php" class="btn btn-outline-light btn-sm me-2"><i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>Iniciar sesión</a>
          <a href="../FlujoSesion/registrarse.php" class="btn btn-light btn-sm"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

