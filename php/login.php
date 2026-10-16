<?php
session_start();
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        echo "<script>alert('Preencha o e-mail e a senha!'); window.history.back();</script>";
        exit;
    }

    $sql = "SELECT id_usuario, nome, email, senha FROM usuario WHERE email = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['email_usuario'] = $usuario['email'];

            // Redireciona para o painel de aulas na raiz
            header("Location: ../aulas.php");
            exit;
        } else {
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('E-mail não encontrado!'); window.history.back();</script>";
        exit;
    }

    $stmt->close();
    $conexao->close();
} else {
    header("Location: ../login.html");
    exit;
}
?>