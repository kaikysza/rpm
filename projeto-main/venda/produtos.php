<?php
session_start();

// Dados do servidor e banco de dados
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'rpm';

// Criação da conexão
$conexao = mysqli_connect($servername, $username, $password, $dbname);

// Consulta ao banco de dados
$sql = "SELECT id, nome, cor, genero, tamanho, quantidade, preco, fornecedor_id, imagem FROM produtos";
$result = mysqli_query($conexao, $sql);

$produtos = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $produtos[] = $row;
    }
}

// Define o cabeçalho da resposta como JSON
header('Content-Type: application/json');
echo json_encode($produtos);

// Fechar a conexão
mysqli_close($conexao);
