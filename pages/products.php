<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . "/../html_fragments/html_head.php"; ?>
    <title>Sistema de vendas</title>
</head>

<body>
</body>
<?php require_once __DIR__ . "/../html_fragments/header.php"; ?>

<br><br><br>

<div class="container-fluid rounded mx-auto w-75 p-3 bg-body-secondary text-center">
    <h1 class="p-2">Cadastro de produtos</h1>
    <hr>
    <form action="#" method="post" class="row justify-content-around">

        <?php

        $productDao = new dao\ProductDAO();

        $product = new stdClass();

        if ($_POST["acao"] == "salvar") {

            $toSave = new models\Product();

            $toSave->id = intval(filter_input(INPUT_POST, "product_id", FILTER_SANITIZE_NUMBER_INT));
            $toSave->name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
            $toSave->price = floatval(filter_input(INPUT_POST, "price", FILTER_SANITIZE_STRING));

            $result = $productDao->save($toSave);
            if ($result != -1 && !(is_int($toSave->id) && $toSave->id > 0)) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        } else if ($_POST["acao"] == "excluir") {

            $id = intval(filter_input(INPUT_POST, "product_id", FILTER_SANITIZE_NUMBER_INT));
            if (is_int($id)) {
                $productDao->delete($id);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }

        if ($_POST["acao"] != "limpar" && isset($_GET["id"]) && !empty($_GET["id"]) && ($productId = intval($_GET["id"]))) {
            $product = $productDao->findById($productId);
        }
        ?>

        <div class="col-2">
            <div class="input-group">
                <span class="input-group-text">Id</span>
                <input type="number" readonly class="form-control" name="product_id" value="<?= $product->id ?>">
            </div>
        </div>
        <div class="col-7">
            <div class="input-group">
                <span class="input-group-text">Nome</span>
                <input type="text" class="form-control" name="name" value="<?= $product->name ?>" required>
            </div>
        </div>
        <div class="col-3">
            <div class="input-group">
                <span class="input-group-text">Valor</span>
                <input class="form-control" name="price" type="number" value="<?= $product->price ?>" step="0.01"
                    required>
            </div>
        </div>
        <div class="col-12 mt-5">
            <div class="row justify-content-center">
                <button type="submit" class="btn btn-success col-3" name="acao" value="salvar">Salvar</button>
                <span class="col-1"></span>
                <button type="submit" class="btn btn-danger col-3" name="acao" value="excluir">Excluir</button>
                <span class="col-1"></span>
                <a href="?id=" class="btn btn-primary col-3" name="acao" value="limpar">Novo</a>
            </div>
        </div>
    </form>
    <hr class="my-5">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th class="w-75">Nome</th>
                <th>Valor</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $list = $productDao->findAll();

            foreach ($list as $product) {
                ?>
                <tr>
                    <td><?= $product->id ?></td>
                    <td class="w-75"><?= $product->name ?></td>
                    <td><?= "R$ " . number_format($product->price, 2) ?> </td>
                    <td>
                        <a href="?id=<?= $product->id ?>" class="btn btn-warning">Alterar</a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

</html>