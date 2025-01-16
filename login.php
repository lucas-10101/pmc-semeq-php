<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <?php
    require_once __DIR__ . "/html_fragments/html_head.php";
    use classes\AuthenticationHandler;
    use classes\SecurityHandler;

    if (SecurityHandler::isAuthenticated()) {
        header("Location: /index.php");
        die;
    }
    ?>
</head>

<body>
    <div class="container mt-5 py-5 border rounded bg-body-secondary w-75 w-sm-100">
        <div class="container">
            <h1>Entrar</h1>
        </div>
        <form id="login-form" class="container mt-5" method="post" action="#">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <h6
            class="text-center mt-3 p-2 bg-danger rounded <?= AuthenticationHandler::$authenticationFailed === true ? "" : "d-none" ?>">
            Usuário não encontrado
        </h6>
    </div>
</body>

</html>