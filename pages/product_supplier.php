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
    <h1 class="p-2">Produtos x Fornecedores</h1>
    <hr>
    <form action="#" method="post" class="row justify-content-around">
        <?php

        $productSupplierDao = new dao\ProductSupplierDAO();

        $productSupplier = new stdClass();
        $toPersist = new models\ProductSupplier();

        if ($_POST["acao"] == "salvar") {

            $toPersist->product_id = intval(filter_input(INPUT_POST, "product_id", FILTER_SANITIZE_NUMBER_INT));
            $toPersist->supplier_id = intval(filter_input(INPUT_POST, "supplier_id", FILTER_SANITIZE_NUMBER_INT));

            $result = $productSupplierDao->save($toPersist);
            if ($result == true) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        } else if ($_POST["acao"] == "excluir") {

            $toPersist->product_id = intval(filter_input(INPUT_POST, "product_id", FILTER_SANITIZE_NUMBER_INT));
            $toPersist->supplier_id = intval(filter_input(INPUT_POST, "supplier_id", FILTER_SANITIZE_NUMBER_INT));

            if (is_int($toPersist->product_id) && is_int($toPersist->supplier_id)) {
                $productSupplierDao->delete($toPersist);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }

        $product_id = isset($_GET["product_id"]) && !empty($_GET["product_id"]) ? intval($_GET["product_id"]) : false;
        $supplier_id = isset($_GET["supplier_id"]) && !empty($_GET["supplier_id"]) ? intval($_GET["supplier_id"]) : false;

        if ($_POST["acao"] != "limpar" && isset($_GET["id"]) && !empty($_GET["id"]) && ($productId = intval($_GET["id"]))) {
            $productSupplier = $productSupplierDao->findById($productId, $supplier_id);
        }
        ?>
        <div class="col-5">
            <div class="input-group">
                <span class="input-group-text">Produto</span>
                <div class="form-control">
                    <select class="select2 w-100" size="size=" 2" name="product_id" required>
                        <?php
                        $productDao = new dao\ProductDAO();

                        $products = $productDao->findAll();
                        foreach ($products as $product) {

                            $selected = false;
                            if ((is_object($productSupplier) && $productSupplier->product_id == $product->id) || $_GET['product_id'] == $product->id) {
                                $selected = true;
                            }

                            ?>
                            <option value="<?= $product->id ?>" <?= $selected ? 'selected' : '' ?>>
                                <?= $product->name ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text">Fornecedor</span>
                <div class="form-control">
                    <select class="select2 w-100" size="2" name="supplier_id" required>
                        <?php
                        $supplierDao = new dao\SupplierDAO();

                        $suppliers = $supplierDao->findAll();
                        foreach ($suppliers as $supplier) {

                            $selected = false;
                            if ((is_object($productSupplier) && $productSupplier->supplier_id == $supplier->id) || $_GET['supplier_id'] == $supplier->id) {
                                $selected = true;
                            }
                            ?>
                            <option value="<?= $supplier->id ?>" <?= $_GET['supplier_id'] == $supplier->id ? 'selected' : '' ?>>
                                <?= $supplier->name ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
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
                <th>Produto</th>
                <th class="w-75">Fornecedor</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $productSuppliers = $productSupplierDao->findAll();

            foreach ($productSuppliers as $productSupplier) {
                ?>
                <tr>
                    <td><?= $productSupplier->product_name ?></td>
                    <td><?= $productSupplier->supplier_name ?></td>
                    <td>
                        <a href="?product_id=<?= $productSupplier->product_id . "&supplier_id=" . $productSupplier->supplier_id ?>"
                            class="btn btn-warning">Alterar</a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

</html>