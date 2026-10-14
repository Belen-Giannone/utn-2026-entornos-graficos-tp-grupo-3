<?php
session_start();

require_once '../../config/conexion.php';

// Obtener los datos enviados por el formulario
$origen = trim($_GET['origen'] ?? '');
$destino = trim($_GET['destino'] ?? '');
$fecha = $_GET['fecha'] ?? '';

$pasajeros = filter_var(
    $_GET['pasajeros'] ?? 1,
    FILTER_VALIDATE_INT
);

$buscar = isset($_GET['buscar']);
$errores = [];
$vuelos = [];

// Validar los datos y buscar vuelos
if ($buscar) {

    if ($origen === '') {
        $errores[] = 'Ingresá la ciudad de origen.';
    }

    if ($destino === '') {
        $errores[] = 'Ingresá la ciudad de destino.';
    }

    if (
        $origen !== '' &&
        $destino !== '' &&
        strcasecmp($origen, $destino) === 0
    ) {
        $errores[] = 'El origen y el destino deben ser diferentes.';
    }

    // Validar fecha
    $fechaValida = false;

    if (
        is_string($fecha) &&
        preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) === 1
    ) {
        $anio = (int) substr($fecha, 0, 4);
        $mes = (int) substr($fecha, 5, 2);
        $dia = (int) substr($fecha, 8, 2);

        $fechaValida = checkdate($mes, $dia, $anio);
    }

    if (!$fechaValida) {
        $errores[] = 'Seleccioná una fecha válida.';
    } elseif ($fecha < date('Y-m-d')) {
        $errores[] = 'La fecha no puede estar en el pasado.';
    }

    // Validar cantidad de pasajeros
    if (
        $pasajeros === false ||
        $pasajeros < 1 ||
        $pasajeros > 4
    ) {
        $errores[] = 'Seleccioná entre 1 y 4 pasajeros.';
    }

    // Consultar vuelos disponibles
    if (empty($errores)) {

        $sql = "
            SELECT
                v.codVuelo,
                v.origen,
                v.destino,
                v.fechaSalida,
                v.fechaLlegada,
                v.precio,
                v.plazasDisponibles,
                a.nombre AS aerolinea
            FROM vuelos v
            INNER JOIN aerolineas a
                ON v.codAerolinea = a.codAerolinea
            WHERE LOWER(TRIM(v.origen)) = LOWER(?)
              AND LOWER(TRIM(v.destino)) = LOWER(?)
              AND DATE(v.fechaSalida) = ?
              AND v.plazasDisponibles >= ?
              AND v.estado = 'programado'
            ORDER BY v.precio ASC
        ";

        $stmt = mysqli_prepare($link, $sql);

        if ($stmt === false) {
            error_log(mysqli_error($link));
            $errores[] = 'No se pudo preparar la búsqueda.';
        } else {

            mysqli_stmt_bind_param(
                $stmt,
                'sssi',
                $origen,
                $destino,
                $fecha,
                $pasajeros
            );

            if (mysqli_stmt_execute($stmt)) {

                $resultado = mysqli_stmt_get_result($stmt);

                if ($resultado !== false) {
                    $vuelos = mysqli_fetch_all(
                        $resultado,
                        MYSQLI_ASSOC
                    );
                } else {
                    $errores[] = 'No se pudieron obtener los resultados.';
                }

            } else {
                error_log(mysqli_stmt_error($stmt));
                $errores[] = 'Ocurrió un error al buscar los vuelos.';
            }

            mysqli_stmt_close($stmt);
        }
    }
}

include '../../Views/Header/header.php';
?>

<main class="container py-5">

    <div class="mb-4">
        <span class="text-primary fw-semibold">ViajAR / Vuelos</span>

        <h1 class="mt-2">Encontrá tu próximo vuelo</h1>

        <p class="text-secondary">
            Ingresá los datos de tu viaje para consultar
            las opciones disponibles.
        </p>
    </div>

    <section class="card border-0 shadow-sm p-4 mb-4">

        <form action="buscar_vuelos.php" method="get">

            <div class="row g-3">

                <div class="col-md-6 col-lg-3">
                    <label for="origen" class="form-label">
                        Ciudad de origen
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="origen"
                        name="origen"
                        placeholder="Ej.: Rosario"
                        value="<?= htmlspecialchars($origen, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                </div>

                <div class="col-md-6 col-lg-3">
                    <label for="destino" class="form-label">
                        Ciudad de destino
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="destino"
                        name="destino"
                        placeholder="Ej.: Buenos Aires"
                        value="<?= htmlspecialchars($destino, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                </div>

                <div class="col-md-6 col-lg-2">
                    <label for="fecha" class="form-label">
                        Fecha de viaje
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="fecha"
                        name="fecha"
                        min="<?= date('Y-m-d') ?>"
                        value="<?= htmlspecialchars(
                            is_string($fecha) ? $fecha : '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        required
                    >
                </div>

                <div class="col-md-6 col-lg-2">
                    <label for="pasajeros" class="form-label">
                        Pasajeros
                    </label>

                    <select
                        class="form-select"
                        id="pasajeros"
                        name="pasajeros"
                        required
                    >
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <option
                                value="<?= $i ?>"
                                <?= (int) $pasajeros === $i ? 'selected' : '' ?>
                            >
                                <?= $i ?>
                                <?= $i === 1 ? 'pasajero' : 'pasajeros' ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-lg-2 d-flex align-items-end">
                    <button
                        type="submit"
                        name="buscar"
                        value="1"
                        class="btn btn-primary w-100"
                    >
                        <i class="bi bi-search me-1" aria-hidden="true"></i>
                        Buscar
                    </button>
                </div>

            </div>
        </form>
    </section>

    <?php if ($buscar): ?>

        <?php if (!empty($errores)): ?>

            <div class="alert alert-danger" role="alert">
                <h2 class="h5">Revisá los datos</h2>

                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li>
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php elseif (empty($vuelos)): ?>

            <div class="alert alert-info" role="status">
                No encontramos vuelos disponibles para esos datos.
                Probá con otra fecha o ruta.
            </div>

        <?php else: ?>

            <h2 class="h4 mb-3">Vuelos disponibles</h2>

            <div class="row g-3">

                <?php foreach ($vuelos as $vuelo): ?>

                    <div class="col-12">
                        <article class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center g-3">

                                    <div class="col-md-3">
                                        <span class="text-secondary small">
                                            Aerolínea
                                        </span>

                                        <h3 class="h6 mt-1">
                                            <?= htmlspecialchars(
                                                $vuelo['aerolinea'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </h3>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center gap-3">

                                            <div>
                                                <strong>
                                                    <?= date(
                                                        'H:i',
                                                        strtotime($vuelo['fechaSalida'])
                                                    ) ?>
                                                </strong>

                                                <div class="small text-secondary">
                                                    <?= htmlspecialchars(
                                                        $vuelo['origen'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>
                                            </div>

                                            <i
                                                class="bi bi-arrow-right text-primary"
                                                aria-hidden="true"
                                            ></i>

                                            <div>
                                                <strong>
                                                    <?= date(
                                                        'H:i',
                                                        strtotime($vuelo['fechaLlegada'])
                                                    ) ?>
                                                </strong>

                                                <div class="small text-secondary">
                                                    <?= htmlspecialchars(
                                                        $vuelo['destino'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="small text-secondary mt-2">
                                            <?= date(
                                                'd/m/Y',
                                                strtotime($vuelo['fechaSalida'])
                                            ) ?>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <span class="text-secondary small">
                                            Por pasajero
                                        </span>

                                        <div class="fs-5 fw-bold text-primary">
                                            $<?= number_format(
                                                (float) $vuelo['precio'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3 text-md-end">
                                        <span class="badge text-bg-success">
                                            <?= (int) $vuelo['plazasDisponibles'] ?>
                                            plazas disponibles
                                        </span>

                                        <p class="small text-secondary mt-2 mb-3">
                                            <?= (int) $pasajeros ?>
                                            pasajero(s)
                                        </p>

                                        <?php
                                        $urlReserva = isset($_SESSION['codUsuario'])
                                            ? 'reservar_vuelo.php'
                                            : '../Sesion/login.php';
                                        ?>

                                        <a
                                            class="btn btn-primary"
                                            href="<?= $urlReserva ?>?codVuelo=<?= (int) $vuelo['codVuelo'] ?>&amp;pasajeros=<?= (int) $pasajeros ?>"
                                        >
                                            <i
                                                class="bi bi-ticket-perforated me-1"
                                                aria-hidden="true"
                                            ></i>
                                            Reservar vuelo
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </article>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    <?php endif; ?>

</main>

<?php include '../../Views/Footer/footer.php'; ?>