<?php

require __DIR__ . "/../autoload.php";

use classes\AuthenticationHandler;
use classes\SecurityHandler;
use dao\SellerDAO;
use dao\ClientDAO;

$name = "";
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary px-5">
    <ul class="navbar-nav">
        <li class="nav-item <?= empty($name) ? "d-none" : ""?>">
            <span><?= $name ?></span>
        </li>
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/index.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/login.php">Login</a>
        </li>

        <?php ?>

        <?php ?>
    </ul>
</nav>