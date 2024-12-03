<?php

require_once 'ProdutoModel.php'; // Incluindo o ProdutoModel

class ProdutoController
{
    private $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel(); // Instancia o ProdutoModel
    }

    // Método para verificar se o produto já existe pelo nome
    public function verificarProdutoExistente($nome)
    {
        return $this->produtoModel->verificarProdutoExistente($nome);
    }

    // Método para cadastrar um novo produto
    public function cadastrar($nome, $despesas_op, $margem_lucro, $ingredientes_quantidades)
    {
        // Validação básica antes de passar para o modelo
        if (empty($nome) || !is_numeric($despesas_op) || !is_numeric($margem_lucro) || empty($ingredientes_quantidades)) {
            throw new Exception("Dados inválidos para cadastrar o produto.");
        }

        // Verificar se o produto já existe
        if ($this->verificarProdutoExistente($nome)) {
            throw new Exception("Erro: O nome do produto já existe. Tente outro nome.");
        }

        // Chama o método de cadastro no modelo
        return $this->produtoModel->cadastrar($nome, $despesas_op, $margem_lucro, $ingredientes_quantidades);
    }

    // Método para obter todos os ingredientes disponíveis
    public function obterIngredientes()
    {
        // Busca os ingredientes disponíveis no banco de dados
        return $this->produtoModel->obterIngredientes();
    }

    // Método para obter os ingredientes de um produto específico
    public function obterIngredientesPorProduto($produtoId)
    {
        // Chama o método do modelo para buscar os ingredientes do produto
        return $this->produtoModel->obterIngredientesPorProduto($produtoId);
    }

    // Método para excluir um produto
    public function excluir($id)
    {
        // Verificar se o ID é válido
        if (!is_numeric($id)) {
            throw new Exception("ID inválido para excluir o produto.");
        }

        // Chama o método de exclusão no modelo e retorna o resultado
        return $this->produtoModel->excluir($id);
    }

    // Lista todos os produtos
    public function listarTodos()
    {
        return $this->produtoModel->listarTodos();
    }

    // Método para buscar um produto pelo ID
    public function obterPorId($id)
    {
        // Busca o produto pelo ID
        if (!is_numeric($id)) {
            throw new Exception("ID inválido para obter o produto.");
        }

        return $this->produtoModel->obterPorId($id);
    }

    // Método para atualizar um produto existente
    public function atualizar($id, $nome, $despesas_op, $margem_lucro)
    {
        // Validação básica antes de chamar o modelo
        if (!is_numeric($id) || empty($nome) || !is_numeric($despesas_op) || !is_numeric($margem_lucro)) {
            throw new Exception("Dados inválidos para atualizar o produto.");
        }

        return $this->produtoModel->atualizar($id, $nome, $despesas_op, $margem_lucro);
    }

    // Método para calcular o preço final de um produto
    public function calcularPrecoFinal($ingredientes_quantidades, $despesas_op, $margem_lucro)
    {
        // Validação dos dados recebidos
        if (empty($ingredientes_quantidades) || !is_numeric($despesas_op) || !is_numeric($margem_lucro)) {
            throw new Exception("Dados inválidos para calcular o preço final.");
        }

        // Chama o método de cálculo do modelo
        return $this->produtoModel->calcularPrecoFinal($ingredientes_quantidades, $despesas_op, $margem_lucro);
    }
}
