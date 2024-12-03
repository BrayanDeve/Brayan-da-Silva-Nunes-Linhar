<?php

require_once 'ProdutoController.php'; 

$produtoController = new ProdutoController();
$mensagem = '';

// Recuperar ingredientes do banco de dados
$ingredientes = $produtoController->obterIngredientes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados enviados pelo formulário
    $nome = trim($_POST['nome']);
    $despesas_op = trim($_POST['despesas_op']);
    $margem_lucro = trim($_POST['margem_lucro']);
    $ingredientes_quantidades = $_POST['quantidade'] ?? [];

    // Validação dos dados
    if (empty($nome)) {
        $mensagem = "O campo 'Nome' é obrigatório.";
    } elseif (!is_numeric($margem_lucro) || $margem_lucro < 0) {
        $mensagem = "A 'Margem de Lucro' deve ser um número válido e positivo.";
    } elseif (!is_numeric($despesas_op) || $despesas_op < 0) {
        $mensagem = "As 'Despesas Operacionais' devem ser um número válido e positivo.";
    } elseif (empty($ingredientes_quantidades)) {
        $mensagem = "Selecione pelo menos um ingrediente.";
    } else {
        // Filtrar ingredientes com quantidades válidas
        $ingredientes_quantidades_filtrados = array_filter($ingredientes_quantidades, function ($quantidade) {
            return is_numeric($quantidade) && $quantidade > 0;
        });

        if (empty($ingredientes_quantidades_filtrados)) {
            $mensagem = "Insira quantidades válidas para os ingredientes selecionados.";
        } else {
            try {
                // Tenta cadastrar o produto
                $produtoController->cadastrar($nome, $despesas_op, $margem_lucro, $ingredientes_quantidades_filtrados);
                $mensagem = "Produto cadastrado com sucesso!";
            } catch (Exception $e) {
                // Caso ocorra algum erro, exibe a mensagem de erro
                $mensagem = $e->getMessage(); // Captura a mensagem de erro da exceção
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - Precificador de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../public/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Cadastrar Produto</h2>

        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo strpos($mensagem, 'sucesso') !== false ? 'success' : 'danger'; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="cadastrar_produto.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($nome ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="despesas_op" class="form-label">Despesas Operacionais (R$)</label>
                <input type="number" name="despesas_op" id="despesas_op" class="form-control" value="<?php echo htmlspecialchars($despesas_op ?? ''); ?>" step="0.01" min="0">
            </div>
            <div class="mb-3">
                <label for="margem_lucro" class="form-label">Margem de Lucro (%)</label>
                <input type="number" name="margem_lucro" id="margem_lucro" class="form-control" value="<?php echo htmlspecialchars($margem_lucro ?? ''); ?>" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ingredientes</label>
                <?php foreach ($ingredientes as $ingrediente): ?>
                    <div class="input-group mb-2">
                        <div class="input-group-text">
                            <input type="checkbox" name="ingrediente[<?php echo $ingrediente['id']; ?>]" value="<?php echo $ingrediente['id']; ?>">
                        </div>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($ingrediente['nome']); ?>" readonly>
                        <input type="number" name="quantidade[<?php echo $ingrediente['id']; ?>]" class="form-control" placeholder="Quantidade" min="0" step="0.01">
                        <span class="input-group-text"><?php echo htmlspecialchars($ingrediente['unidade_medida']); ?></span>
                        <span class="input-group-text">R$ <?php echo number_format($ingrediente['custo'], 2, ',', '.'); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar Produto</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
