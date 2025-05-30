<?php
class Funcionario
{
    public $codigo, $nome, $data_nascimento, $cargo, $login, $senha, $foto;

    //Metodo construtor
    function __construct($codigo, $nome, $data_nascimento, $cargo, $login, $senha, $foto)
    {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->data_nascimento = $data_nascimento;
        $this->cargo = $cargo;
        $this->login = $login;
        $this->senha = $senha;
        $this->foto = $foto;
    }

    // Função para validar o login
    function validaFuncionarioSenha($login, $senha, $senha_criptografada)
    {
        // Verifica se o login corresponde
        if ($login == $this->login) {
            // Verifica a senha usando password_verify
            if (password_verify($senha, $senha_criptografada)) {
                return true; // Senha válida
            }
        }
        return false; // Senha ou login inválido
    }
}
