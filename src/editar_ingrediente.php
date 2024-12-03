<?php
session_start(); // Iniciar a sessão

require_once 'IngredienteController.php';

$ingredienteController = new IngredienteController();

// Verificar se o ID foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $ingrediente = $ingredienteController->obterPorId($id);
} else {
    // Se não houver ID, redirecionar ou exibir mensagem de erro
    die("Ingrediente não encontrado.");
}

// Processar o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $custo = trim($_POST['custo']);
    $unidade_medida = trim($_POST['unidade_medida']);
    $volume = trim($_POST['volume']);

    // Atualizar o ingrediente
    $ingredienteController->atualizar($id, $nome, $custo, $unidade_medida, $volume);

    // Armazenar a mensagem de sucesso na sessão
    $_SESSION['alert_message_atualizado'] = "Ingrediente '$nome' atualizado com sucesso!";

    // Redirecionar após a atualização
    header("Location: visualizar_ingrediente.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Ingrediente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../public/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Editar Ingrediente</h2>
        <form method="POST" action="editar_ingrediente.php?id=<?php echo $ingrediente['id']; ?>">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Ingrediente</label>
                <input type="text" name="nome" id="nome" class="form-control" value="<?php echo htmlspecialchars($ingrediente['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="custo" class="form-label">Custo (R$)</label>
                <input type="number" name="custo" id="custo" class="form-control" step="0.01" value="<?php echo htmlspecialchars($ingrediente['custo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="unidade_medida" class="form-label">Unidade de Medida</label>
                <select name="unidade_medida" class="form-control" required>
                    <option value="G" <?php echo isset($ingrediente['unidade_medida']) && $ingrediente['unidade_medida'] == 'G' ? 'selected' : ''; ?>>Gramas</option>
                    <option value="ML" <?php echo isset($ingrediente['unidade_medida']) && $ingrediente['unidade_medida'] == 'ML' ? 'selected' : ''; ?>>Mililitro</option>
                    <option value="UND" <?php echo isset($ingrediente['unidade_medida']) && $ingrediente['unidade_medida'] == 'UND' ? 'selected' : ''; ?>>Unidade</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="volume" class="form-label">Volume</label>
                <input type="number" name="volume" id="volume" class="form-control" step="0.01" value="<?php echo htmlspecialchars($ingrediente['volume']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Atualizar Ingrediente</button>
        </form>
    </div>
</body>

</html>