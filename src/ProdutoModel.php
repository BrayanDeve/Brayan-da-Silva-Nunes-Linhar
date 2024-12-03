<?php

require_once 'Database.php';

class ProdutoModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection(); // Obtém a conexão com o banco de dados
    }

    // Verificar se o produto já existe pelo nome
    public function verificarProdutoExistente($nome)
    {
        $sql = "SELECT COUNT(*) FROM produtos WHERE nome = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0; // Retorna verdadeiro se o produto já existir
    }

    // Obter produto por ID
    public function obterPorId($id)
    {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function excluir($id)
    {
        // Primeiro, exclui as associações na tabela produtos_ingredientes
        $sql = "DELETE FROM produtos_ingredientes WHERE produto_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Depois, exclui o produto da tabela produtos
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute(); // Retorna true ou false com base no sucesso da execução
    }


    // Obter os ingredientes e suas quantidades associados a um produto
    public function obterIngredientesPorProduto($produtoId)
    {
        $sql = "SELECT ingrediente_id, quantidade 
            FROM produtos_ingredientes 
            WHERE produto_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $produtoId);
        $stmt->execute();
        $result = $stmt->get_result();

        $ingredientes = [];
        while ($row = $result->fetch_assoc()) {
            $ingredientes[$row['ingrediente_id']] = $row['quantidade'];
        }

        return $ingredientes;
    }

    // Atualizar produto
    public function atualizar($id, $nome, $despesas_op, $margem_lucro)
    {
        $sql = "UPDATE produtos SET nome = ?, despesas_op = ?, margem_lucro = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sddi", $nome, $despesas_op, $margem_lucro, $id);

        return $stmt->execute();
    }

    // Obter ingrediente por ID
    public function obterIngredientePorId($id)
    {
        $sql = "SELECT * FROM ingredientes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Calcular custo de um ingrediente com base na quantidade usada
    public function calcularCustoIngrediente($ingrediente, $quantidade)
    {
        if (empty($ingrediente) || !isset($ingrediente['custo'], $ingrediente['volume'])) {
            throw new Exception("Ingrediente inválido para cálculo de custo.");
        }

        $custo_por_unidade = $ingrediente['custo'] / $ingrediente['volume']; // Custo por unidade (grama, ml, etc.)
        return $custo_por_unidade * $quantidade;
    }

    // Calcular preço final do produto
    public function calcularPrecoFinal($ingredientes_quantidades, $despesas_op, $margem_lucro)
    {
        $total_custo_ingredientes = 0;

        // Calculando o custo total de todos os ingredientes
        foreach ($ingredientes_quantidades as $ingrediente_id => $quantidade) {
            $ingrediente = $this->obterIngredientePorId($ingrediente_id);
            $total_custo_ingredientes += $this->calcularCustoIngrediente($ingrediente, $quantidade); // Soma os custos dos ingredientes
        }

        $total_custo_produto = $total_custo_ingredientes + $despesas_op;

        // Calcula o preço final usando o Markup
        $markup = 1 + ($margem_lucro / 100);
        $preco_final = $total_custo_produto * $markup;

        return $preco_final;
    }

    public function listarTodos()
    {
        $sql = "SELECT * FROM produtos"; // Consulta para buscar todos os produtos
        $result = $this->db->query($sql); // Executa a consulta
        return $result->fetch_all(MYSQLI_ASSOC); // Retorna os resultados como um array associativo
    }

    // Obter todos os ingredientes
    public function obterIngredientes()
    {
        $sql = "SELECT id, nome, unidade_medida, custo, volume FROM ingredientes";
        $result = $this->db->query($sql);

        if (!$result) {
            die("Erro ao obter ingredientes: " . $this->db->error);
        }

        $ingredientes = [];
        while ($row = $result->fetch_assoc()) {
            $ingredientes[] = $row;
        }

        return $ingredientes;
    }

    // Cadastrar novo produto e seus ingredientes
    public function cadastrar($nome, $despesas_op, $margem_lucro, $ingredientes_quantidades)
    {
        // Verificar se o produto já existe
        if ($this->verificarProdutoExistente($nome)) {
            throw new Exception("Erro: O nome do produto já existe. Tente outro nome.");
        }

        // Inicia uma transação
        $this->db->begin_transaction();

        try {
            // Calcular o custo dos ingredientes
            $total_custo_ingredientes = 0;
            foreach ($ingredientes_quantidades as $ingrediente_id => $quantidade) {
                // Obter os dados do ingrediente
                $ingrediente = $this->obterIngredientePorId($ingrediente_id);
                $total_custo_ingredientes += $this->calcularCustoIngrediente($ingrediente, $quantidade);
            }

            // Calcular o custo total do produto
            $total_custo_produto = $total_custo_ingredientes + $despesas_op;

            // Calcular o preço de venda com a margem de lucro
            $preco_venda = $total_custo_produto * (1 + ($margem_lucro / 100));

            // Inserir o produto na tabela de produtos, incluindo custo_ingredientes e preco_venda
            $stmt = $this->db->prepare("INSERT INTO produtos (nome, despesas_op, margem_lucro, custo_ingredientes, preco_venda) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Erro ao preparar consulta: " . $this->db->error);
            }

            // Certifique-se de que os tipos de dados estão corretos
            $stmt->bind_param("sddds", $nome, $despesas_op, $margem_lucro, $total_custo_ingredientes, $preco_venda);
            $stmt->execute();

            $produto_id = $this->db->insert_id; // Obtém o ID do produto inserido

            $stmt->close();

            // Inserir os ingredientes do produto na tabela de relacionamento
            $stmt = $this->db->prepare("INSERT INTO produtos_ingredientes (produto_id, ingrediente_id, quantidade) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Erro ao preparar consulta de ingredientes: " . $this->db->error);
            }

            foreach ($ingredientes_quantidades as $ingrediente_id => $quantidade) {
                $stmt->bind_param("iid", $produto_id, $ingrediente_id, $quantidade);
                $stmt->execute();
            }

            $stmt->close();

            // Confirma a transação
            $this->db->commit();

            return true;
        } catch (Exception $e) {
            // Reverte a transação em caso de erro
            $this->db->rollback();

            error_log("Erro ao cadastrar produto: " . $e->getMessage());
            return false;
        }
    }
}
