<?php
// Conexão com o banco de dados
$servername = "localhost";  // Nome do servidor
$username = "root";         // Usuário do banco (padrão do XAMPP)
$password = "";             // Senha do banco (padrão do XAMPP)
$dbname = "precificador";   // Nome do banco de dados (substitua pelo seu nome)

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>