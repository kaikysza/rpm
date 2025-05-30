<?php
header('Content-Type: application/json');

// Importando arquivo de conexão
include_once '../classe/conexao.php';

// Lê o corpo da requisição JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Verifica se os dados necessários foram enviados
if (isset($dados['cpfCliente'], $dados['nomeCliente'], $dados['formaPagamento'], $dados['funcionario'], $dados['dataVenda'], $dados['itens']) && !empty($dados['itens'])) {
    $cpfCliente = $dados['cpfCliente'];
    $nomeCliente = $dados['nomeCliente'];
    $formaPagamento = $dados['formaPagamento'];
    $funcionario = $dados['funcionario'];
    $dataVenda = $dados['dataVenda'];
    $itens = $dados['itens'];  // Itens do carrinho (array de objetos)

    // Prepara a inserção na tabela de vendas
    $sqlVenda = "INSERT INTO vendas (data_venda, forma_pagamento, cliente_id, funcionario_id) 
                 VALUES (STR_TO_DATE(?, '%d/%m/%Y'), ?, (SELECT id FROM clientes WHERE cpf = ? LIMIT 1), 
                 (SELECT id FROM funcionarios WHERE nome = ? LIMIT 1))";
    $stmtVenda = $conexao->prepare($sqlVenda);

    // Faz a inserção da venda
    $stmtVenda->bind_param("ssss", $dataVenda, $formaPagamento, $cpfCliente, $funcionario);
    $stmtVenda->execute();

    // Verifica se a venda foi registrada com sucesso
    if ($stmtVenda->affected_rows > 0) {
        // Obtém o ID da venda recém-criada
        $vendaId = $stmtVenda->insert_id;

        // Prepara a inserção dos itens
        $sqlItens = "INSERT INTO itens (venda_id, produto_id, preco_venda, quantidade) 
                     VALUES (?, ?, ?, ?)";
        $stmtItens = $conexao->prepare($sqlItens);

        // Variável para verificar se o estoque foi atualizado com sucesso
        $estoqueAtualizado = true;

        // Insere os itens no banco e atualiza o estoque
        foreach ($itens as $item) {
            $produtoId = $item['produtoId'];
            $precoVenda = $item['precoVenda'];
            $quantidadeVendida = $item['quantidade']; // quantidade do item vendido

            // Verifica se a quantidade em estoque é suficiente
            $sqlVerificaEstoque = "SELECT quantidade FROM produtos WHERE id = ?";
            $stmtVerificaEstoque = $conexao->prepare($sqlVerificaEstoque);
            $stmtVerificaEstoque->bind_param("i", $produtoId);
            $stmtVerificaEstoque->execute();
            $stmtVerificaEstoque->bind_result($quantidadeEstoque);
            $stmtVerificaEstoque->fetch();

            // Se a quantidade vendida for maior que a quantidade em estoque, interrompe o processo
            if ($quantidadeVendida > $quantidadeEstoque) {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Estoque insuficiente para o produto ' . $produtoId]);
                exit; // Interrompe a execução
            }

            // Fechando o resultado da consulta de estoque antes de continuar
            $stmtVerificaEstoque->free_result();

            // Insere o item na tabela 'itens'
            $stmtItens->bind_param("iidi", $vendaId, $produtoId, $precoVenda, $quantidadeVendida);
            $stmtItens->execute();

            // Agora, atualiza o estoque do produto
            $sqlEstoque = "UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?";
            $stmtEstoque = $conexao->prepare($sqlEstoque);
            $stmtEstoque->bind_param("ii", $quantidadeVendida, $produtoId);
            $stmtEstoque->execute();

            // Se a atualização do estoque falhar, marca o erro e interrompe a transação
            if ($stmtEstoque->affected_rows == 0) {
                $estoqueAtualizado = false;
                break;  // Se falhou a atualização do estoque, interrompe o processo
            }
        }

        // Se todos os itens foram inseridos e o estoque foi atualizado, retorna sucesso
        if ($stmtItens->affected_rows > 0 && $estoqueAtualizado) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Venda realizada com sucesso!']);
        } else {
            $conexao->query("DELETE FROM itens WHERE venda_id = $vendaId");
            $conexao->query("DELETE FROM vendas WHERE id = $vendaId");
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao processar os itens ou atualizar o estoque.']);
        }
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao registrar a venda.']);
    }
} else {
    // Caso algum campo necessário esteja faltando
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos para registrar a venda.']);
}
