<?php
$host = 'localhost';
$dbname = 'rpm';
$username = 'root';
$password = '';

// Criação da conexão
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Verifica se o CPF foi enviado via POST
$data = json_decode(file_get_contents('php://input'), true);
$cpf = $data['cpf'] ?? '';

if ($cpf) {
    // Consulta ao banco de dados
    $stmt = $pdo->prepare("SELECT nome FROM clientes WHERE cpf = :cpf");
    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $stmt->execute();

    // Verifica se o cliente foi encontrado
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        // Cliente encontrado, retorna o nome
        echo json_encode([
            'cadastrado' => true,
            'nome' => $cliente['nome']
        ]);
    } else {
        // Cliente não encontrado
        echo json_encode([
            'cadastrado' => false
        ]);
    }
} else {
    // Caso não tenha CPF
    echo json_encode([
        'cadastrado' => false
    ]);
}
