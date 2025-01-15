<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <?php require_once __DIR__ . "/html_fragments/html_head.php"; ?>
</head>

<body>
    <div class="container mt-5 py-5 border rounded bg-body-secondary w-75 w-sm-100">
        <div class="container">
            <h1>Entrar</h1>
        </div>
        <form id="login-form" class="container mt-5">
            <div class="mb-3">
                <label for="username" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="username">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" autocomplete="off">
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</body>

</html>