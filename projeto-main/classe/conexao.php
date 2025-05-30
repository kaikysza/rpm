<?php
// Dados do servidor e banco de dados.
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'rpm';


//Criação da variavel conexao para receber os valores das variaveis criadas
$conexao = mysqli_connect($servername, $username, $password, $dbname);

//Teste de conexao, caso esteja tudo certo a conexao é realizada com sucesso.
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}
