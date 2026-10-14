<?php
declare(strict_types=1);

require_once __DIR__ . '/Connection.php';

function erro(string $mensagem, int $codigo = 1): never
{
    fwrite(STDERR, "\033[31mErro: {$mensagem}\033[0m\n");
    exit($codigo);
}

function informar(string $mensagem): void
{
    fwrite(STDOUT, "\033[32m{$mensagem}\033[0m\n");
}

function migrations(): array
{
    $arquivos = glob(__DIR__ . '/migrations/*.php') ?: [];
    sort($arquivos, SORT_STRING);
    $resultado = [];
    foreach ($arquivos as $arquivo) {
        $migration = require $arquivo;
        if (!is_array($migration) || !isset($migration['up'], $migration['down'])) {
            throw new RuntimeException('Migration inválida: ' . basename($arquivo));
        }
        $resultado[basename($arquivo)] = $migration;
    }
    return $resultado;
}

function garantirControle(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (
        nome VARCHAR(255) NOT NULL PRIMARY KEY,
        lote INT UNSIGNED NOT NULL,
        executada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function aplicadas(PDO $pdo): array
{
    return $pdo->query('SELECT nome, lote FROM schema_migrations ORDER BY lote, nome')->fetchAll();
}

function executarUp(PDO $pdo, array $migrations): void
{
    garantirControle($pdo);
    $aplicadas = array_column(aplicadas($pdo), 'nome');
    $pendentes = array_diff(array_keys($migrations), $aplicadas);
    if ($pendentes === []) {
        informar('O banco já está atualizado.');
        return;
    }
    $lote = (int) ($pdo->query('SELECT COALESCE(MAX(lote), 0) FROM schema_migrations')->fetchColumn()) + 1;
    $registrar = $pdo->prepare('INSERT INTO schema_migrations (nome, lote) VALUES (?, ?)');
    foreach ($pendentes as $nome) {
        try {
            // DDL do MySQL confirma a transação implicitamente; cada migration é um único CREATE TABLE.
            $pdo->exec($migrations[$nome]['up']);
            $registrar->execute([$nome, $lote]);
            informar("Aplicada: {$nome}");
        } catch (Throwable $e) {
            throw new RuntimeException("Falha em {$nome}: {$e->getMessage()}", 0, $e);
        }
    }
}

function executarDown(PDO $pdo, array $migrations): void
{
    garantirControle($pdo);
    $lote = $pdo->query('SELECT MAX(lote) FROM schema_migrations')->fetchColumn();
    if ($lote === false || $lote === null) {
        informar('Não há migrations aplicadas para reverter.');
        return;
    }
    $consulta = $pdo->prepare('SELECT nome FROM schema_migrations WHERE lote = ? ORDER BY nome DESC');
    $consulta->execute([$lote]);
    $remover = $pdo->prepare('DELETE FROM schema_migrations WHERE nome = ?');
    foreach ($consulta->fetchAll(PDO::FETCH_COLUMN) as $nome) {
        if (!isset($migrations[$nome])) {
            throw new RuntimeException("Arquivo da migration {$nome} não foi encontrado.");
        }
        try {
            $pdo->exec($migrations[$nome]['down']);
            $remover->execute([$nome]);
            informar("Revertida: {$nome}");
        } catch (Throwable $e) {
            throw new RuntimeException("Falha ao reverter {$nome}: {$e->getMessage()}", 0, $e);
        }
    }
}

function executarStatus(PDO $pdo, array $migrations): void
{
    $existe = $pdo->query("SHOW TABLES LIKE 'schema_migrations'")->fetchColumn();
    $nomesAplicados = $existe ? array_column(aplicadas($pdo), 'nome') : [];
    foreach (array_keys($migrations) as $nome) {
        $estado = in_array($nome, $nomesAplicados, true) ? 'aplicada' : 'pendente';
        fwrite(STDOUT, sprintf("%-42s %s\n", $nome, $estado));
    }
}

try {
    $acao = $argv[1] ?? '';
    if (!in_array($acao, ['up', 'down', 'status', 'fresh'], true)) {
        erro('Uso: php database/migrate.php {up|down|status|fresh}', 2);
    }
    $pdo = criarConexao();
    $migrations = migrations();

    if ($acao === 'status') {
        executarStatus($pdo, $migrations);
    } elseif ($acao === 'up') {
        executarUp($pdo, $migrations);
    } elseif ($acao === 'down') {
        executarDown($pdo, $migrations);
    } else {
        if (strtolower(getenv('APP_ENV') ?: 'development') === 'production') {
            erro('O comando fresh está bloqueado quando APP_ENV=production.');
        }
        garantirControle($pdo);
        fwrite(STDOUT, "Isso removerá todas as tabelas gerenciadas por migrations e recriará o schema. Digite FRESH para continuar: ");
        $confirmacao = trim((string) fgets(STDIN));
        if ($confirmacao !== 'FRESH') {
            informar('Operação cancelada.');
            exit(0);
        }
        while ((int) $pdo->query('SELECT COUNT(*) FROM schema_migrations')->fetchColumn() > 0) {
            executarDown($pdo, $migrations);
        }
        executarUp($pdo, $migrations);
    }
} catch (Throwable $e) {
    erro($e->getMessage());
}
