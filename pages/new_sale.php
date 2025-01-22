<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . "/../html_fragments/html_head.php"; ?>
    <title>Sistema de vendas</title>
</head>

<body class="w-100">


    <?php

    include_once __DIR__ . "/../html_fragments/header.php";

    $person = classes\SessionManager::getUserPerson();

    ?>

    <br><br><br>
    <div class="container-fluid rounded mx-auto w-75 p-3 bg-body-secondary text-center">
        <form action="#" method="post" class="row justify-content-start p-3">
            <h1 class="col-12 p-4 mb-5 border-bottom border-3 border-dark-subtle">Lançamento de venda</h1>
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Venda nº</span>
                    <input type="text" readonly class="form-control shadow-none" value="">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Data Venda</span>
                    <input type="date" required class="form-control shadow-none" name="sell_date"
                        value="<?= date("Y-m-d") ?>">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text">Vendedor</span>
                    <input type="text" readonly class="form-control shadow-none" name="seller_name"
                        value="<?= $person->name ?>">
                </div>
            </div>
            <span class="col-12 my-2"></span>
            <div class="col-12 col-md-8">
                <div class="input-group">
                    <span class="input-group-text">Cliente</span>
                    <div class="form-control">
                        <select class="select2-autocomplete-client-name w-100" name="client_id" required>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text">Total</span>
                    <input type="text" readonly class="form-control shadow-none" id="total" value="R$ 0.00">
                </div>
            </div>

            <h1 class="col-12 p-4 my-5 border-bottom border-3 border-dark-subtle">Dados para entrega</h1>

            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">CEP</span>
                    <input type="text" required title="Formato 00000-000" class="form-control shadow-none"
                        name="postal_code" value="" previous="" oninput="postalCodeFieldAutocomplete(event)"
                        minlength="9" maxlength="9">
                </div>
            </div>
            <span class="col-12 my-2"></span>
            <div class="col-12 col-md-2">
                <div class="input-group">
                    <span class="input-group-text">Nº</span>
                    <input type="number" required class="form-control shadow-none" address-form-data name="house_number"
                        min=1 value="">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text">Logradouro</span>
                    <input type="text" required class="form-control shadow-none" name="street" address-form-data
                        value="">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text">Bairro</span>
                    <input type="text" required class="form-control shadow-none" name="district" address-form-data
                        value="">
                </div>
            </div>
            <span class="col-12 my-2"></span>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text">Estado</span>
                    <input type="text" required class="form-control shadow-none" name="state" address-form-data
                        value="">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text">Cidade</span>
                    <input type="text" required class="form-control shadow-none" name="city" address-form-data value="">
                </div>
            </div>
            <div class="col-12 mt-2">
                <label class="form-label">Complemento</label>
                <textarea class="form-control shadow-none" name="complement" address-form-data rows="3"
                    maxlength="200"></textarea>
            </div>

            <h1 class="col-12 p-4 my-5 border-bottom border-3 border-dark-subtle">Adicionar produtos</h1>

            <div class="col-12 col-md-8">
                <div class="input-group">
                    <span class="input-group-text">Produto</span>
                    <div class="form-control">
                        <select class="select2-autocomplete-product-name list-product-suppliers w-100"
                            id="product-selection">
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <div class="input-group">
                    <span class="input-group-text">Itens</span>
                    <input type="number" class="form-control shadow-none" id="product-selection-quantity" min=1
                        value="1">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-success w-100" onclick="addSelectedProductToCart()"
                    type="button">Adicionar</button>
            </div>
            <div class="col-12 mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fornecedores do produto selecionado</th>
                        </tr>
                    </thead>
                    <tbody product-suppliers-list>

                    </tbody>
                </table>
            </div>

            <h1 class="col-12 p-4 my-5 border-bottom border-3 border-dark-subtle">Carrinho</h1>

            <div class="col-12 mt-4 p-0 p-md-1">
                <table class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody product-sell-list>

                    </tbody>
                </table>
            </div>

            <h1 class="col-12 p-4 my-5 border-bottom border-3 border-dark-subtle">Carrinho</h1>


            <div class="col-12 mt-5">
                <button class="btn btn-success col-12 col-md-3" name="acao" value="salvar" type="button"
                    onclick="saveSell()">Gerar Venda</button>
            </div>
        </form>
    </div>
    <br><br><br><br><br><br><br><br>
    <div class="modal fade" data-bs-backdrop="static" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title">Aviso</h1>
                </div>
                <p class="modal-body" id="modal-content"></p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>