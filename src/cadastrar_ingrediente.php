<?php
// Inicie a conexão com o banco de dados
require_once 'conexao.php'; // Inclua o arquivo de conexão com o banco de dados

$nome_ingrediente = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar dados do formulário
    $nome = trim($_POST['nome']);
    $custo = trim($_POST['custo']);
    $unidade_medida = trim($_POST['unidade_medida']);
    $volume = trim($_POST['volume']);

    // Validar os dados
    if (empty($nome) || empty($custo) || empty($unidade_medida) || empty($volume)) {
        $nome_ingrediente = "Todos os campos são obrigatórios.";
    } elseif (!is_numeric($custo) || $custo < 0) {
        $nome_ingrediente = "O custo deve ser um número positivo.";
    } else {
        // Verificar se o ingrediente já existe no banco de dados
        $sql_verifica = "SELECT COUNT(*) AS total FROM ingredientes WHERE nome = ?";
        $stmt_verifica = $conn->prepare($sql_verifica);

        if ($stmt_verifica === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt_verifica->bind_param("s", $nome);
        $stmt_verifica->execute();
        $resultado = $stmt_verifica->get_result();
        $dados = $resultado->fetch_assoc();

        if ($dados['total'] > 0) {
            $nome_ingrediente = "Ingrediente já cadastrado.";
        } else {
            // Inserir o ingrediente no banco de dados
            $sql = "INSERT INTO ingredientes (nome, custo, unidade_medida, volume) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }

            $stmt->bind_param("sdsd", $nome, $custo, $unidade_medida, $volume); // "s" para string, "d" para decimal
            $result = $stmt->execute();

            if ($result) {
                $nome_ingrediente = "Ingrediente cadastrado com sucesso!";
            } else {
                $nome_ingrediente = "Erro ao cadastrar ingrediente: " . $stmt->error;
            }

            $stmt->close();
        }

        $stmt_verifica->close();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Ingrediente - Precificador de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?php include '../public/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Cadastrar Ingrediente</h2>
        <?php if ($nome_ingrediente): ?>
            <?php if ($nome_ingrediente == 'Ingrediente já cadastrado.'): ?>
                <div class="alert alert-danger"><?php echo $nome_ingrediente; ?></div>
            <?php else: ?>
                <div class="alert alert-success"><?php echo $nome_ingrediente; ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" action="cadastrar_ingrediente.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Ingrediente</label>
                <input type="text" name="nome" id="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="custo" class="form-label">custo (R$)</label>
                <input type="number" name="custo" id="custo" class="form-control" step="0.01" min="0" required>
            </div>
            <div class="mb-3">
                <label for="unidade_medida" class="form-label">Unidade de Medida</label>
                <select name="unidade_medida" required>
                    <option value="G">Gramas</option>
                    <option value="ML">Mililitro</option>
                    <option value="UND">Unidade</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="volume" class="form-label">Volume do Produto</label>
                <input type="number" name="volume" id="volume" class="form-control" step="0.01" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>