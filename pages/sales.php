<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . "/html_fragments/html_head.php"; ?>
    <title>Sistema de vendas</title>
</head>

<body>
</body>
<?php include_once __DIR__ . "/../../html_fragments/header.php"; ?>
<h1 class="p-2">Produtos x Fornecedores</h1>
<hr>
<form action="#" method="post" class="row justify-content-around">
    <div class="col-12 mt-5">

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

        <div class="row justify-content-center">
            <button type="submit" class="btn btn-success col-3" name="acao" value="salvar">Salvar</button>
            <span class="col-1"></span>
            <button type="submit" class="btn btn-danger col-3" name="acao" value="excluir">Excluir</button>
            <span class="col-1"></span>
            <a href="?id=" class="btn btn-primary col-3" name="acao" value="limpar">Novo</a>
        </div>
    </div>
</form>

</html>