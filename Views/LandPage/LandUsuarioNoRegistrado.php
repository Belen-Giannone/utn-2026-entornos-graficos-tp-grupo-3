<?php
session_start();
include '../../config/conexion.php';
include '../../Views/Header/header.php';
?>
<main>
    <section class="hero-section">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="eyebrow"><i class="bi bi-stars me-2" aria-hidden="true"></i>Tu próximo destino empieza acá</span>
                    <h1 class="hero-title mt-3">Viajá más simple con <span>ViajAR</span></h1>
                    <p class="hero-copy">
                        Encontrá vuelos, compará opciones y prepará tu próxima aventura desde un solo lugar.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a class="btn btn-warning btn-lg" href="#buscador-vuelos">
                            <i class="bi bi-search me-2" aria-hidden="true"></i>Buscar vuelos
                        </a>
                        <a class="btn btn-outline-primary btn-lg" href="../FlujoSesion/registrarse.php">
                            Crear una cuenta
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-illustration" aria-hidden="true">
                        <i class="bi bi-cloud-fill cloud cloud-one"></i>
                        <i class="bi bi-cloud-fill cloud cloud-two"></i>
                        <div class="route-line"></div>
                        <div class="plane-badge"><i class="bi bi-airplane-fill"></i></div>
                        <div class="destination-pin pin-one"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="destination-pin pin-two"><i class="bi bi-geo-alt-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="buscador-vuelos" class="search-section py-5">
        <div class="container">
            <div class="section-heading mb-4">
                <span class="section-kicker">Planificá tu viaje</span>
                <h2>¿A dónde querés volar?</h2>
                <p>Completá los datos para comenzar a buscar tu próximo vuelo.</p>
            </div>

            <form class="flight-search-form bg-white shadow-sm" action="" method="get">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="origen"><i class="bi bi-airplane-engines me-1" aria-hidden="true"></i>Desde</label>
                        <input class="form-control" id="origen" name="origen" type="text" placeholder="Ciudad o aeropuerto">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="destino"><i class="bi bi-geo-alt me-1" aria-hidden="true"></i>Hasta</label>
                        <input class="form-control" id="destino" name="destino" type="text" placeholder="Ciudad o aeropuerto">
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label class="form-label" for="fecha"><i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Fecha</label>
                        <input class="form-control" id="fecha" name="fecha" type="date">
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <label class="form-label" for="pasajeros"><i class="bi bi-people me-1" aria-hidden="true"></i>Pasajeros</label>
                        <select class="form-select" id="pasajeros" name="pasajeros">
                            <option value="1">1 pasajero</option>
                            <option value="2">2 pasajeros</option>
                            <option value="3">3 pasajeros</option>
                            <option value="4">4 pasajeros</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="bi bi-search me-1" aria-hidden="true"></i>Buscar
                        </button>
                    </div>
                </div>
            </form>
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
                        <div class="benefit-icon icon-blue"><i class="bi bi-search" aria-hidden="true"></i></div>
                        <h3>Buscá con facilidad</h3>
                        <p>Encontrá alternativas de vuelo de forma clara y rápida.</p>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="benefit-card h-100">
                        <div class="benefit-icon icon-yellow"><i class="bi bi-ticket-perforated" aria-hidden="true"></i></div>
                        <h3>Reservá tu lugar</h3>
                        <p>Guardá tus vuelos y consultá tus reservas cuando quieras.</p>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="benefit-card h-100">
                        <div class="benefit-icon icon-green"><i class="bi bi-shield-check" aria-hidden="true"></i></div>
                        <h3>Tené todo organizado</h3>
                        <p>Accedé a tu historial de compras desde tu cuenta personal.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include '../../Views/Footer/footer.php'; ?>
