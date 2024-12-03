<?php
session_start(); // Iniciar a sessão

require_once 'ProdutoController.php';

$produtoController = new ProdutoController();
$mensagem = '';

// Verifica se o ID do produto foi passado na URL
if (isset($_GET['id'])) {
    $produtoId = $_GET['id'];
    $produto = $produtoController->obterPorId($produtoId);

    // Se o formulário for enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Dados enviados pelo formulário
        $nome = trim($_POST['nome']);
        $despesas_op = trim($_POST['despesas_op']);
        $margem_lucro = trim($_POST['margem_lucro']);

        // Validar dados do produto
        if (empty($nome)) {
            $mensagem = "O campo 'Nome' é obrigatório.";
        } elseif (!is_numeric($margem_lucro) || $margem_lucro < 0) {
            $mensagem = "A 'Margem de Lucro' deve ser um número válido e positivo.";
        } elseif (!is_numeric($despesas_op) || $despesas_op < 0) {
            $mensagem = "As 'Despesas Operacionais' devem ser um número válido e positivo.";
        } else {
            // Tenta atualizar o produto
            try {
                $produtoController->atualizar($produtoId, $nome, $despesas_op, $margem_lucro);

                // Armazenar a mensagem de sucesso na sessão
                $_SESSION['alert_message_atualizado'] = "Produto '$nome' atualizado com sucesso!";

                // Redireciona de volta para visualizar_produto.php com a mensagem
                header('Location: visualizar_produto.php?id=' . $produtoId);
                exit;
            } catch (Exception $e) {
                $mensagem = $e->getMessage();
            }
        }
    }
} else {
    die("Produto não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../public/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Produto</h2>

        <!-- Exibir a mensagem de sucesso de atualização -->
        <?php if (!empty($_SESSION['alert_message_atualizado'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['alert_message_atualizado']; ?>
            </div>
            <?php unset($_SESSION['alert_message_atualizado']); // Limpar a mensagem da sessão 
            ?>
        <?php endif; ?>

        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo strpos($mensagem, 'sucesso') !== false ? 'success' : 'danger'; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="editar_produto.php?id=<?php echo $produtoId; ?>">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="despesas_op" class="form-label">Despesas Operacionais (R$)</label>
                <input type="number" name="despesas_op" id="despesas_op" class="form-control" value="<?php echo htmlspecialchars($produto['despesas_op']); ?>" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="margem_lucro" class="form-label">Margem de Lucro (%)</label>
                <input type="number" name="margem_lucro" id="margem_lucro" class="form-control" value="<?php echo htmlspecialchars($produto['margem_lucro']); ?>" step="0.01" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Atualizar Produto</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>