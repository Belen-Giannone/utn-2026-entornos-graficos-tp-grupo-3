<?php
session_start();
require_once '../../config/conexion.php';

$errores = [];
$nombre = '';
$apellido = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmarPassword = $_POST['confirmarPassword'] ?? '';

    if ($nombre === '' || $apellido === '' || $email === '' ||
        $password === '' || $confirmarPassword === '') {
        $errores[] = 'Completá todos los campos.';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingresá un email válido.';
    }

    if (strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    }

    if ($password !== $confirmarPassword) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    if (empty($errores)) {
        $consulta = mysqli_prepare(
            $link,
            'SELECT codUsuario FROM usuarios WHERE email = ?'
        );

        mysqli_stmt_bind_param($consulta, 's', $email);
        mysqli_stmt_execute($consulta);
        mysqli_stmt_store_result($consulta);

        if (mysqli_stmt_num_rows($consulta) > 0) {
            $errores[] = 'Ya existe una cuenta registrada con ese email.';
        }

        mysqli_stmt_close($consulta);
    }

    if (empty($errores)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $tipoUsuario = 'cliente';

        $consulta = mysqli_prepare(
            $link,
            'INSERT INTO usuarios
            (nombre, apellido, email, password, tipoUsuario, fechaRegistro, activo)
            VALUES (?, ?, ?, ?, ?, NOW(), 1)'
        );

        mysqli_stmt_bind_param(
            $consulta,
            'sssss',
            $nombre,
            $apellido,
            $email,
            $passwordHash,
            $tipoUsuario
        );

        if (mysqli_stmt_execute($consulta)) {
            mysqli_stmt_close($consulta);
            header('Location: login.php?registro=exitoso');
            exit;
        } else {
            $errores[] = 'No se pudo crear la cuenta. Revisá los datos e intentá nuevamente.';
        }

        mysqli_stmt_close($consulta);
    }
}
?>

<?php include '../../Views/Header/header.php'; ?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 text-center mb-4">Crear una cuenta</h1>

                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errores as $error): ?>
                                    <li>
                                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nombre"
                                name="nombre"
                                value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input
                                type="text"
                                class="form-control"
                                id="apellido"
                                name="apellido"
                                value="<?= htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
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
                                minlength="8"
                                required
                            >
                            <div class="form-text">
                                Debe tener al menos 8 caracteres.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirmarPassword" class="form-label">
                                Confirmar contraseña
                            </label>
                            <input
                                type="password"
                                class="form-control"
                                id="confirmarPassword"
                                name="confirmarPassword"
                                minlength="8"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Crear cuenta
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0">
                        ¿Ya tenés una cuenta?
                        <a href="login.php">Iniciá sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../../Views/Footer/footer.php'; ?>