<?php

require_once 'ProdutoController.php'; // Inclui o controlador do produto
require_once 'IngredienteController.php'; // Inclui o controlador do ingrediente

$mensagem = '';
$produtos = [];

// Instanciando o controlador de Produtos
$produtoController = new ProdutoController();

// Carregar todos os produtos
$produtos = $produtoController->listarTodos(); // Método para listar todos os produtos

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos - Precificador de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include '../public/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Produtos</h2>
        <?php if ($mensagem): ?>
            <div class="alert alert-info"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <?php if ($produtos): ?>
            <table class="table table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Criado em</th>
                        <th>Custo dos Ingredientes (R$)</th>
                        <th>Despesas Operacionais (R$)</th>
                        <th>Margem de Lucro (%)</th>
                        <th>Preço de Venda (R$)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['id']); ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($produto['criado_em']))); ?></td>
                            <td><?php echo htmlspecialchars(number_format($produto['custo_ingredientes'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars(number_format($produto['despesas_op'], 2, ',', '.')); ?></td>
                            <td><?php echo htmlspecialchars($produto['margem_lucro']); ?>%</td>
                            <td><?php echo htmlspecialchars(number_format($produto['preco_venda'], 2, ',', '.')); ?></td>
                            <td>
                                <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="excluir_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este produto?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-danger">Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>
