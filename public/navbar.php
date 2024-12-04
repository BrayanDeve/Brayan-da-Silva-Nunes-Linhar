<?php
// public/navbar.php
?>

<style>
    button:hover{
        background: #20becf;
    };
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../public/dashboard.php">Precificador de Produtos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../src/cadastrar_ingrediente.php">Cadastrar Ingrediente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../src/cadastrar_produto.php">Cadastrar Produto</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../src/visualizar_produto.php">Visualizar Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../src/visualizar_ingrediente.php">Visualizar Ingredientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../src/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>