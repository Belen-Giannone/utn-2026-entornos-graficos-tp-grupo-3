<?php
$tipoUsuario = $_SESSION['tipoUsuario'] ?? null;
?>

<footer class="site-footer bg-dark text-light">
	<div class="container py-5">
		<div class="row g-4">
			<div class="col-lg-4 col-md-6">
				<a class="footer-brand d-inline-flex align-items-center gap-2 mb-3" href="../../index.php">
					<span class="footer-brand-mark" aria-hidden="true"><i class="bi bi-airplane-fill"></i></span>
					<span>ViajAR</span>
				</a>
				<p class="footer-copy mb-0">
					Gestión simple de vuelos, reservas y compras para que cada viaje empiece mejor.
				</p>
			</div>

			<nav class="col-lg-3 col-md-6" aria-label="Mapa del sitio">
				<h2 class="footer-title"><i class="bi bi-map me-2" aria-hidden="true"></i>Mapa del sitio</h2>
				<ul class="footer-links list-unstyled mb-0">
					<li><a href="../../index.php">Inicio</a></li>
					<?php if ($tipoUsuario === 'administrador'): ?>
						<li><a href="../FuncionesAdmin/aerolineas-index.php">Aerolíneas</a></li>
						<li><a href="../FuncionesAdmin/auditoria-promociones.php">Promociones</a></li>
						<li><a href="../FuncionesAdmin/novedades-index.php">Novedades</a></li>
						<li><a href="../FuncionesAdmin/global-reports.php">Reportes</a></li>
					<?php elseif ($tipoUsuario === 'ceo'): ?>
						<li><a href="../FuncionesCEO/vuelos-index.php">Vuelos</a></li>
						<li><a href="../FuncionesCEO/promociones-index.php">Promociones</a></li>
						<li><a href="../FuncionesCEO/reportes-ceo.php">Reportes</a></li>
					<?php elseif ($tipoUsuario === 'usuario'): ?>
						<li><a href="../FuncionesUsuario/buscar_vuelos.php">Buscar vuelos</a></li>
						<li><a href="../FuncionesUsuario/mis_reservas.php">Mis reservas</a></li>
						<li><a href="../FuncionesUsuario/historial_compras.php">Historial de compras</a></li>
						<li><a href="../FuncionesUsuario/promociones.php">Promociones</a></li>
					<?php else: ?>
						<li><a href="../LandPage/LandUsuarioNoRegistrado.php">Aerolíneas</a></li>
						<li><a href="../LandPage/LandUsuarioNoRegistrado.php#seccion-vuelos">Vuelos disponibles</a></li>
						<li><a href="../FlujoSesion/login.php">Iniciar sesión</a></li>
					<?php endif; ?>
				</ul>
			</nav>

			<div class="col-lg-3 col-md-6">
				<h2 class="footer-title"><i class="bi bi-info-circle me-2" aria-hidden="true"></i>ViajAR</h2>
				<ul class="footer-details list-unstyled mb-0">
					<li><i class="bi bi-check2-circle" aria-hidden="true"></i>Buscá vuelos</li>
					<li><i class="bi bi-check2-circle" aria-hidden="true"></i>Gestioná tus reservas</li>
					<li><i class="bi bi-check2-circle" aria-hidden="true"></i>Consultá tus compras</li>
				</ul>
			</div>

			<div class="col-lg-2 col-md-6">
				<h2 class="footer-title"><i class="bi bi-mortarboard me-2" aria-hidden="true"></i>Proyecto</h2>
				<p class="footer-copy mb-0">Trabajo práctico de Entornos Gráficos 2026.<br>UTN.</p>
			</div>
		</div>

		<div class="footer-bottom d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-4">
			<small>© 2026 ViajAR. Todos los derechos reservados.</small>
			<small>Una forma más simple de viajar.</small>
		</div>
	</div>
</footer>
