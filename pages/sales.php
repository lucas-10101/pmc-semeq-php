<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . "/../html_fragments/html_head.php"; ?>
    <script defer src="/pages/scripts/sales.js"></script>
    <title>Sistema de vendas</title>
</head>

<body>
    <?php include_once __DIR__ . "/../html_fragments/header.php"; ?>
    <br><br><br>

    <div class="container-fluid rounded mx-auto w-75 p-3 bg-body-secondary text-center">
        <h1 class="p-2">Histórico de vendas</h1>
        <hr>
        <div class="accordion" id="salesList">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h6>
                            Venda nº 11 - Data da venda: 21/01/2024 - Total: R$ 12005.00
                            <br>
                            Cliente: Lucas Rafael de Quadros
                        </h6>
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tv</td>
                                    <td>10 Un</td>
                                </tr>
                                <tr>
                                    <td>Celular</td>
                                    <td>3 Un</td>
                                </tr>
                                <tr>
                                    <td>Carro</td>
                                    <td>4 Un</td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <div class="container-fluid border border-black rounded">
                            <h2 class="text-center bg-body-secondary m-2 p-1 rounded">Entrega</h2>
                            <h4 class="text-start"> Cidade: Limeira/SP - 13485-075 | Endereço: 106, Iolando Donnati</h4>
                            <h4 class="text-start"> Complemento: teste teste teste</h4>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>