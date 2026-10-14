<?php
session_start();
require_once '../../config/conexion.php';

$error = '';
$email = '';

// Conservar el vuelo elegido, si el login viene desde una reserva.
$codVuelo = filter_var(
    $_POST['codVuelo'] ?? $_GET['codVuelo'] ?? null,
    FILTER_VALIDATE_INT
);

$pasajeros = filter_var(
    $_POST['pasajeros'] ?? $_GET['pasajeros'] ?? 1,
    FILTER_VALIDATE_INT
);

// Validar los datos de la selección.
if ($codVuelo === false || $codVuelo < 1) {
    $codVuelo = null;
}

if ($pasajeros === false || $pasajeros < 1 || $pasajeros > 4) {
    $pasajeros = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Completá el email y la contraseña.';
    } else {

        $consulta = mysqli_prepare(
            $link,
            'SELECT codUsuario, nombre, password, tipoUsuario, activo
             FROM usuarios
             WHERE email = ?
             LIMIT 1'
        );

        if ($consulta === false) {
            $error = 'No se pudo procesar el inicio de sesión.';
        } else {

            mysqli_stmt_bind_param($consulta, 's', $email);
            mysqli_stmt_execute($consulta);

            $resultado = mysqli_stmt_get_result($consulta);
            $usuario = mysqli_fetch_assoc($resultado);

            mysqli_stmt_close($consulta);

            if (
                $usuario &&
                password_verify($password, $usuario['password']) &&
                (int) $usuario['activo'] === 1
            ) {
                session_regenerate_id(true);

                $_SESSION['codUsuario'] = $usuario['codUsuario'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['tipoUsuario'] = $usuario['tipoUsuario'];

                // Si viene desde una reserva, continuar con ese vuelo.
                if ($codVuelo !== null) {
                    header(
                        'Location: ../FuncionesUsuario/reservar_vuelo.php?codVuelo=' .
                        (int) $codVuelo .
                        '&pasajeros=' . (int) $pasajeros
                    );
                    exit;
                }

                // Si inició sesión desde otro lugar, ir al inicio.
                header('Location: ../../index.php');
                exit;

            } else {
                $error = 'Email o contraseña incorrectos, o cuenta inactiva.';
            }
        }
    }
}
?>

<?php include '../../Views/Header/header.php'; ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <h1 class="h3 text-center mb-4">Iniciar sesión</h1>

                    <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exitoso'): ?>
                        <div class="alert alert-success">
                            ¡Tu cuenta se creó correctamente! Ya podés iniciar sesión.
                        </div>
                    <?php endif; ?>

                    <?php if ($error !== ''): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="post">

                        <input
                            type="hidden"
                            name="codVuelo"
                            value="<?= $codVuelo !== null ? (int) $codVuelo : '' ?>"
                        >

                        <input
                            type="hidden"
                            name="pasajeros"
                            value="<?= (int) $pasajeros ?>"
                        >

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                                autocomplete="email"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                autocomplete="current-password"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Iniciar sesión
                        </button>

                    </form>

                    <p class="text-center mt-3 mb-0">
                        ¿Todavía no tenés cuenta?
                        <a href="registrarse.php">Registrate acá</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</main>

<?php include '../../Views/Footer/footer.php'; ?>