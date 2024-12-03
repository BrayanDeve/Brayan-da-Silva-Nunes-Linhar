<?php
session_start(); // Iniciar a sessão

require_once 'ProdutoController.php'; // Inclui o controlador do produto

$produtoController = new ProdutoController(); // Instancia o controlador de produtos

// Verificar se o ID foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obter o nome do produto para exibir no alert
    $produto = $produtoController->obterPorId($id);
    $nome_produto = $produto['nome'];

    // Excluir o produto
    $produtoController->excluir($id);

    // Armazenar a mensagem de sucesso na sessão
    $_SESSION['alert_message_excluido'] = "produto '$nome_produto' excluído com sucesso!";

    // Redirecionar após a exclusão
    header("Location: visualizar_produto.php");
    exit();
} else {
    // Se não houver ID, redirecionar ou exibir mensagem de erro
    die("produto não encontrado.");
}
?>