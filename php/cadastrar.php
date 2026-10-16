<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($nome) || empty($email) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos!'); window.history.back();</script>";
        exit;
    }

    $sql_verifica = "SELECT id_usuario FROM usuario WHERE email = ?";
    $stmt_verifica = $conexao->prepare($sql_verifica);
    $stmt_verifica->bind_param("s", $email);
    $stmt_verifica->execute();
    $stmt_verifica->store_result();

    if ($stmt_verifica->num_rows > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!'); window.history.back();</script>";
        exit;
    }
    $stmt_verifica->close();

    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

    $sql_inserir = "INSERT INTO usuario (nome, email, senha, total_progresso_usuario) VALUES (?, ?, ?, 0)";
    $stmt = $conexao->prepare($sql_inserir);
    $stmt->bind_param("sss", $nome, $email, $senha_criptografada);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastro realizado com sucesso! Faça o login.'); window.location.href = '../login.html';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $conexao->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conexao->close();
} else {
    header("Location: ../index.html");
    exit;
}
?>