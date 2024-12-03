<?php

require_once 'IngredienteModel.php'; // Inclui o modelo de ingrediente

class IngredienteController
{
    private $ingredienteModel;

    public function __construct()
    {
        $this->ingredienteModel = new IngredienteModel();
    }

    // Lista todos os ingredientes
    public function listarTodos()
    {
        return $this->ingredienteModel->listarTodos();
    }

    // Cadastra um novo ingrediente
    public function cadastrar($nome, $custo, $unidade_medida, $volume)
    {
        return $this->ingredienteModel->cadastrar($nome, $custo, $unidade_medida, $volume);
    }

    // Atualiza um ingrediente existente
    public function atualizar($id, $nome, $custo, $unidade_medida, $volume)
    {
        return $this->ingredienteModel->atualizar($id, $nome, $custo, $unidade_medida, $volume);
    }

    // Exclui um ingrediente
    public function excluir($id)
    {
        return $this->ingredienteModel->excluir($id);
    }

    // Obtém um ingrediente por ID
    public function obterPorId($id)
    {
        return $this->ingredienteModel->obterPorId($id);
    }
}

