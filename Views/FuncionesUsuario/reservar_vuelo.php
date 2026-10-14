<?php
session_start();
require_once '../../config/conexion.php';

$errores = [];
$reservaCreada = false;
$vuelo = null;

if (!isset($_SESSION['codUsuario'])) {
    $codVuelo = filter_var(
        $_GET['codVuelo'] ?? null,
        FILTER_VALIDATE_INT
    );

    $pasajeros = filter_var(
        $_GET['pasajeros'] ?? 1,
        FILTER_VALIDATE_INT
    );

    if ($codVuelo === false || $codVuelo < 1) {
        $codVuelo = null;
    }

    if ($pasajeros === false || $pasajeros < 1 || $pasajeros > 4) {
        $pasajeros = 1;
    }

    $urlLogin = '../Sesion/login.php';

    if ($codVuelo !== null) {
        $urlLogin .= '?codVuelo=' . (int) $codVuelo .
            '&pasajeros=' . (int) $pasajeros;
    }

    header('Location: ' . $urlLogin);
    exit;
}

$codVuelo = filter_var(
    $_POST['codVuelo'] ?? $_GET['codVuelo'] ?? null,
    FILTER_VALIDATE_INT
);

$pasajeros = filter_var(
    $_POST['pasajeros'] ?? $_GET['pasajeros'] ?? 1,
    FILTER_VALIDATE_INT
);

if ($codVuelo === false || $codVuelo === null || $codVuelo < 1) {
    $errores[] = 'El vuelo seleccionado no es válido.';
}

if ($pasajeros === false || $pasajeros < 1 || $pasajeros > 4) {
    $errores[] = 'La cantidad de pasajeros no es válida.';
}

// Obtener información del vuelo.
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
            v.estado,
            a.nombre AS aerolinea
        FROM vuelos v
        INNER JOIN aerolineas a
            ON v.codAerolinea = a.codAerolinea
        WHERE v.codVuelo = ?
        LIMIT 1
    ";

    $consulta = mysqli_prepare($link, $sql);

    if ($consulta === false) {
        $errores[] = 'No se pudo consultar el vuelo.';
    } else {

        mysqli_stmt_bind_param($consulta, 'i', $codVuelo);
        mysqli_stmt_execute($consulta);

        $resultado = mysqli_stmt_get_result($consulta);
        $vuelo = mysqli_fetch_assoc($resultado);

        mysqli_stmt_close($consulta);

        if (!$vuelo) {
            $errores[] = 'El vuelo seleccionado no existe.';
        } elseif ($vuelo['estado'] !== 'programado') {
            $errores[] = 'Este vuelo ya no está disponible para reservar.';
        } elseif ((int) $vuelo['plazasDisponibles'] < $pasajeros) {
            $errores[] = 'No hay suficientes plazas disponibles para esa cantidad de pasajeros.';
        } elseif (strtotime($vuelo['fechaSalida']) < time()) {
            $errores[] = 'No se puede reservar un vuelo que ya salió.';
        }
    }
}

// Registrar la reserva al confirmar.
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['confirmar']) &&
    empty($errores)
) {

    $codUsuario = (int) $_SESSION['codUsuario'];
    $precioTotal = (float) $vuelo['precio'] * (int) $pasajeros;

    mysqli_begin_transaction($link);

    try {

        // Descontar las plazas solo si el vuelo sigue programado
        // y todavía tiene suficientes lugares.
        $actualizar = mysqli_prepare(
            $link,
            "UPDATE vuelos
             SET plazasDisponibles = plazasDisponibles - ?
             WHERE codVuelo = ?
               AND estado = 'programado'
               AND plazasDisponibles >= ?
               AND fechaSalida >= NOW()"
        );

        if ($actualizar === false) {
            throw new Exception('No se pudo verificar la disponibilidad.');
        }

        mysqli_stmt_bind_param(
            $actualizar,
            'iii',
            $pasajeros,
            $codVuelo,
            $pasajeros
        );

        if (!mysqli_stmt_execute($actualizar)) {
            mysqli_stmt_close($actualizar);
            throw new Exception('No se pudo actualizar la disponibilidad.');
        }

        $filasActualizadas = mysqli_stmt_affected_rows($actualizar);
        mysqli_stmt_close($actualizar);

        if ($filasActualizadas !== 1) {
            throw new Exception(
                'El vuelo ya no tiene suficientes plazas disponibles. Volvé a buscar vuelos.'
            );
        }

        // Crear la reserva con estado pendiente.
        $insertar = mysqli_prepare(
            $link,
            "INSERT INTO reservas
                (codUsuario, codVuelo, fechaReserva, cantidadPasajeros, precioTotal, estado)
             VALUES (?, ?, NOW(), ?, ?, 'pendiente')"
        );

        if ($insertar === false) {
            throw new Exception('No se pudo preparar la reserva.');
        }

        mysqli_stmt_bind_param(
            $insertar,
            'iiid',
            $codUsuario,
            $codVuelo,
            $pasajeros,
            $precioTotal
        );

        if (!mysqli_stmt_execute($insertar)) {
            mysqli_stmt_close($insertar);
            throw new Exception('No se pudo registrar la reserva.');
        }

        $codReserva = mysqli_insert_id($link);
        mysqli_stmt_close($insertar);

        mysqli_commit($link);

        $reservaCreada = true;

    } catch (Throwable $e) {

        mysqli_rollback($link);

        error_log($e->getMessage());
        $errores[] = $e->getMessage();
    }
}

include '../../Views/Header/header.php';
?>

<main class="container py-5">

    <div class="mb-4">
        <span class="text-primary fw-semibold">ViajAR / Reservas</span>
        <h1 class="mt-2">Reservá tu vuelo</h1>
        <p class="text-secondary">
            Revisá los datos antes de confirmar tu reserva.
        </p>
    </div>

    <?php if ($reservaCreada): ?>

        <div class="alert alert-success" role="status">
            <h2 class="h4">¡Reserva registrada!</h2>

            <p>
                Tu reserva quedó registrada con estado pendiente.
            </p>

            <p class="mb-0">
                Número de reserva:
                <strong><?= (int) $codReserva ?></strong>
            </p>
        </div>

        <a
            href="../../index.php"
            class="btn btn-primary"
        >
            Volver al inicio
        </a>

    <?php else: ?>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li>
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($vuelo && empty($errores)): ?>

            <?php
            $precioTotal = (float) $vuelo['precio'] * (int) $pasajeros;
            ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <h2 class="h5 mb-3">
                        <?= htmlspecialchars($vuelo['aerolinea'], ENT_QUOTES, 'UTF-8') ?>
                    </h2>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <span class="text-secondary small">Origen</span>
                            <p class="fw-semibold mb-0">
                                <?= htmlspecialchars($vuelo['origen'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <span class="text-secondary small">Destino</span>
                            <p class="fw-semibold mb-0">
                                <?= htmlspecialchars($vuelo['destino'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <span class="text-secondary small">Salida</span>
                            <p class="mb-0">
                                <?= date('d/m/Y H:i', strtotime($vuelo['fechaSalida'])) ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <span class="text-secondary small">Llegada</span>
                            <p class="mb-0">
                                <?= date('d/m/Y H:i', strtotime($vuelo['fechaLlegada'])) ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <span class="text-secondary small">Pasajeros</span>
                            <p class="mb-0"><?= (int) $pasajeros ?></p>
                        </div>

                        <div class="col-md-6">
                            <span class="text-secondary small">Precio por pasajero</span>
                            <p class="mb-0">
                                $<?= number_format((float) $vuelo['precio'], 2, ',', '.') ?>
                            </p>
                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Total del viaje</span>

                        <span class="fs-4 fw-bold text-primary">
                            $<?= number_format($precioTotal, 2, ',', '.') ?>
                        </span>
                    </div>

                    <form action="reservar_vuelo.php" method="post">

                        <input
                            type="hidden"
                            name="codVuelo"
                            value="<?= (int) $codVuelo ?>"
                        >

                        <input
                            type="hidden"
                            name="pasajeros"
                            value="<?= (int) $pasajeros ?>"
                        >

                        <button
                            type="submit"
                            name="confirmar"
                            value="1"
                            class="btn btn-primary w-100"
                        >
                            Confirmar reserva
                        </button>

                    </form>

                </div>
            </div>

        <?php endif; ?>

        <a
            href="buscar_vuelos.php"
            class="btn btn-outline-secondary mt-3"
        >
            Volver a buscar vuelos
        </a>

    <?php endif; ?>

</main>

<?php include '../../Views/Footer/footer.php'; ?>