<?php

require __DIR__ . "/../autoload.php";

use classes\SecurityHandler;
use classes\SessionManager;

$person = SessionManager::getUserPerson();

?>

<nav class="navbar navbar-expand-lg bg-body-tertiary px-5">
    <ul class="navbar-nav">
        <li class="nav-item border-end rounded<?= $person == null ? "d-none" : "" ?>">
            <span class="nav-link fw-bold"><?= $person != null ? 'Bem vindo ' . $person->name : "" ?></span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/index.php">Home</a>
        </li>

        <?php if (!SecurityHandler::isAuthenticated()) { ?>
            <li class="nav-item">
                <a class="nav-link" href="/login.php">Login</a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="/logout.php">Logout</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/pages/sales.php">Historico de vendas</a>
            </li>
        <?php } ?>

        <?php
        if (SecurityHandler::isAuthenticated() && SessionManager::getUser()->role = "SELLER") { ?>
            <li class="nav-item">
                <a class="nav-link" href="/pages/products.php">Produtos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/pages/suppliers.php">Fornecedores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/pages/product_supplier.php">Produto x Fornecedor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/pages/new_sale.php">Nova Venda</a>
            </li>
        <?php } ?>

    </ul>
</nav>