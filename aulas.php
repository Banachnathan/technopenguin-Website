<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html");
    exit;
}

include("php/conexao.php");
?>

<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel de Aulas TechnoPenguin</title>
  <link rel="stylesheet" href="assets/css/global.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header style="padding: 20px; background: #1a2e46; color: white;">
    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome_usuario']); ?>! 🐧</h1>
    <p>Estás pronto para continuar a tua jornada de programação?</p>
    <a href="php/logout.php" style="color: #ff4d4d; font-weight: bold;">Terminar Sessão</a>
  </header>

  <main style="padding: 20px;">
    <h2>Os teus Cursos e Módulos</h2>
    <p>Listagem de conteúdos</p>
    
    <div class="cursos-lista" style="margin-top: 20px;">
      <?php
      $sql_cursos = "SELECT * FROM curso";
      $resultado_cursos = $conexao->query($sql_cursos);

      if ($resultado_cursos->num_rows > 0) {
          while($curso = $resultado_cursos->fetch_assoc()) {
              echo "<div style='background: #f4f4f4; color: #333; padding: 15px; margin-bottom: 15px; border-radius: 8px; border-left: 5px solid #1a2e46;'>";
              echo "<h3>" . htmlspecialchars($curso['titulo_curso']) . "</h3>";
              echo "<p>" . htmlspecialchars($curso['descricao_curso']) . "</p>";
              echo "<small><strong>Nível:</strong> " . htmlspecialchars($curso['nivel_dificuldade_curso']) . "</small>";
              echo "</div>";
          }
      } else {
          echo "<p>Ainda não há cursos cadastrados no banco de dados.</p>";
      }
      ?>
    </div>
  </main>
</body>
</html>