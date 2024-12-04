<?php
require_once 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        header('Location: ../public/dashboard.php');
        exit();
    } else {
        header("Location: ../public/index.php?error=credenciais_invalidas");
        exit();
    }
}
?>