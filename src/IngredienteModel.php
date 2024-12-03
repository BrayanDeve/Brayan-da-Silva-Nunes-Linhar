<?php

require_once 'Database.php'; // Inclui a conexão com o banco de dados

class IngredienteModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection(); // Obtém a conexão com o MySQLi
    }
    
    // Lista todos os ingredientes
    public function listarTodos()
    {
        $sql = "SELECT * FROM ingredientes";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC); // Retorna todos os ingredientes como um array associativo
    }

    // Cadastra um novo ingrediente
    public function cadastrar($nome, $custo, $unidade_medida, $volume)
    {
        $sql = "INSERT INTO ingredientes (nome, custo, unidade_medida, volume) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $nome, $custo, $unidade_medida, $volume); // Bind dos parâmetros
        return $stmt->execute(); // Executa a query
    }

    // Atualiza um ingrediente
    public function atualizar($id, $nome, $custo, $unidade_medida, $volume)
    {
        $sql = "UPDATE ingredientes SET nome = ?, custo = ?, unidade_medida = ?, volume = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sdsii", $nome, $custo, $unidade_medida, $volume, $id); // Bind dos parâmetros
        return $stmt->execute(); // Executa a query
    }

    // Exclui um ingrediente
    public function excluir($id)
    {
        $sql = "DELETE FROM ingredientes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id); // Bind do parâmetro
        return $stmt->execute(); // Executa a query
    }

    // Obtém um ingrediente por ID
    public function obterPorId($id)
    {
        $sql = "SELECT * FROM ingredientes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id); // Bind do parâmetro
        $stmt->execute(); // Executa a query
        $result = $stmt->get_result(); // Obtém o resultado da query
        return $result->fetch_assoc(); // Retorna o ingrediente
    }
}
