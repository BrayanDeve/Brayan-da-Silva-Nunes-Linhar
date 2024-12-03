<?php
session_start(); // Iniciar a sessão

require_once 'IngredienteController.php';

// Instancia o controlador
$ingredienteController = new IngredienteController();

// Obtém a lista de ingredientes
$ingredientes = $ingredienteController->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Visualizar Ingredientes - Precificador de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include '../public/navbar.php'; ?>

    <!-- Exibir mensagem de ingrediente atualizado -->
    <?php if (!empty($_SESSION['alert_message_atualizado'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['alert_message_atualizado']; ?>
        </div>
        <?php unset($_SESSION['alert_message_atualizado']); // Limpa a mensagem da sessão 
        ?>
        <!-- Exibir mensagem de ingrediente excluido -->
    <?php elseif (!empty($_SESSION['alert_message_excluido'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['alert_message_excluido']; ?>
        </div>
        <?php unset($_SESSION['alert_message_excluido']); // Limpa a mensagem da sessão 
        ?>
    <?php endif; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Ingredientes</h2>
        <?php if (!empty($ingredientes)): ?>
            <table class="table table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Custo (R$)</th>
                        <th>Und Medida</th>
                        <th>Volume</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ingredientes as $ingrediente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ingrediente['id']); ?></td>
                            <td><?php echo htmlspecialchars($ingrediente['nome']); ?></td>
                            <td><?php echo number_format($ingrediente['custo'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($ingrediente['unidade_medida']); ?></td>
                            <td><?php echo htmlspecialchars($ingrediente['volume']); ?></td>
                            <td>
                                <a href="editar_ingrediente.php?id=<?php echo $ingrediente['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="excluir_ingrediente.php?id=<?php echo $ingrediente['id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir este ingrediente?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Nenhum ingrediente cadastrado.</p>
        <?php endif; ?>
        <a href="cadastrar_ingrediente.php" class="btn btn-primary w-100 mt-3">Cadastrar Novo Ingrediente</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>