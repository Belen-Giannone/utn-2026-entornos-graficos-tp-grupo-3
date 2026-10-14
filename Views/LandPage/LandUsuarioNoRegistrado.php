<?php
session_start();
include '../../config/conexion.php';
include '../../Views/Header/header.php';
?>

<main>
    <section class="hero-section">
        <div class="container py-5">
            <div class="hero-content row align-items-center">
                <div class="col-lg-6">
                    <span class="eyebrow">
                        <i class="bi bi-stars me-2" aria-hidden="true"></i>
                        Tu próximo destino empieza acá
                    </span>

                    <h1 class="hero-title mt-3">
                        Viajá más simple con <span>ViajAR</span>
                    </h1>

                    <p class="hero-copy">
                        Encontrá vuelos, compará opciones y prepará tu próxima aventura desde un solo lugar.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        <a class="btn btn-warning btn-lg"
                           href="../FuncionesUsuario/buscar_vuelos.php">
                            <i class="bi bi-search me-2" aria-hidden="true"></i>
                            Buscar vuelos
                        </a>

                        <a class="btn btn-outline-primary btn-lg"
                           href="../FlujoSesion/registrarse.php">
                            Crear una cuenta
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="benefits-section py-5">
        <div class="container">
            <div class="section-heading text-center mb-4">
                <span class="section-kicker">Todo en un mismo lugar</span>
                <h2>Una experiencia pensada para vos</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <article class="benefit-card h-100">
                        <div class="benefit-icon icon-blue">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </div>

                        <h3>Buscá con facilidad</h3>

                        <p>
                            Encontrá alternativas de vuelo de forma clara y rápida.
                        </p>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="benefit-card h-100">
                        <div class="benefit-icon icon-yellow">
                            <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
                        </div>

                        <h3>Reservá tu lugar</h3>

                        <p>
                            Guardá tus vuelos y consultá tus reservas cuando quieras.
                        </p>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="benefit-card h-100">
                        <div class="benefit-icon icon-green">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                        </div>

                        <h3>Tené todo organizado</h3>

                        <p>
                            Accedé a tu historial de compras desde tu cuenta personal.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../../Views/Footer/footer.php'; ?>
