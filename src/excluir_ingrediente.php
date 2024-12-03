<?php
session_start(); // Iniciar a sessão

require_once 'IngredienteController.php';

$ingredienteController = new IngredienteController();

// Verificar se o ID foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obter o nome do ingrediente para exibir no alert
    $ingrediente = $ingredienteController->obterPorId($id);
    $nome_ingrediente = $ingrediente['nome'];

    // Excluir o ingrediente
    $ingredienteController->excluir($id);

    // Armazenar a mensagem de sucesso na sessão
    $_SESSION['alert_message_excluido'] = "Ingrediente '$nome_ingrediente' excluído com sucesso!";

    // Redirecionar após a exclusão
    header("Location: visualizar_ingrediente.php");
    exit();
} else {
    // Se não houver ID, redirecionar ou exibir mensagem de erro
    die("Ingrediente não encontrado.");
}
?>
