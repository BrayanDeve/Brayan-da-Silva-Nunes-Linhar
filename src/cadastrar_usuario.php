<?php
// Iniciar a sessão
session_start();

// Incluir a conexão com o banco de dados
require_once 'conexao.php';

// Verificando se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebe os dados do formulário
    $nome_usuario = $_POST['nome_usuario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validação simples para garantir que os campos não estão vazios
    if (empty($nome_usuario) || empty($email) || empty($senha)) {
        echo "Todos os campos são obrigatórios.";
        exit();
    }

    // Verificando se o email já existe
    $sql_check = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        // Mensagem estilizada para e-mail já cadastrado
        echo '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>E-mail já cadastrado</title>
            <link rel="stylesheet" type="text/css" href="../public/style.css">
            <style>
                .message-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: flex-start;
                    height: 100vh;
                    background: var(--gradient-bg);
                    color: #fff;
                    text-align: center;
                    padding: 20px;
                    padding-top: 90px;
                }
                .message-container h1 {
                    font-size: 2rem;
                    margin-bottom: 20px;
                }
                .message-container p {
                    font-size: 1.2rem;
                    margin-bottom: 30px;
                }
                .message-container a {
                    text-decoration: none;
                    padding: 10px 20px;
                    background-color: #055470;
                    color: #fff;
                    border-radius: 5px;
                    font-size: 1rem;
                    transition: background-color 0.3s;
                }
                .message-container a:hover {
                    background-color: #20becf;
                }
            </style>
        </head>
        <body>
            <div class="message-container">
                <h1>E-mail já cadastrado!</h1>
                <p>O e-mail informado já está registrado no sistema. Tente outro ou faça login.</p>
                <a href="../public/login.php">Voltar ao Registro</a>
            </div>
        </body>
        </html>
        ';
        exit();
    }

    // Criptografando a senha antes de armazená-la no banco
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Preparando a consulta SQL para inserir os dados no banco
    $sql = "INSERT INTO usuarios (nome_usuario, email, senha) VALUES ('$nome_usuario', '$email', '$senha_hash')";

    // Executando a consulta
    if ($conn->query($sql) === TRUE) {
        // Cadastro bem-sucedido, redirecionando para a página de login
        header("Location: ../public/login.php?success=cadastro_sucesso");
        exit();
    } else {
        // Caso ocorra algum erro
        echo "Erro ao cadastrar: " . $conn->error;
    }

    // Fechando a conexão com o banco de dados
    $conn->close();
}
