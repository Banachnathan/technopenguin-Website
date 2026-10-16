<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";          // No XAMPP a senha é vazia
$banco = "technopenguin";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);
$conexao->set_charset("utf8mb4");

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
?>