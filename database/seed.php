<?php
declare(strict_types=1);

require_once __DIR__ . '/Connection.php';

function garantirRegistro(PDO $pdo, string $sqlBuscar, array $parametros, string $sqlInserir, array $dados): int
{
    $buscar = $pdo->prepare($sqlBuscar);
    $buscar->execute($parametros);
    $id = $buscar->fetchColumn();
    if ($id !== false) {
        return (int) $id;
    }
    $inserir = $pdo->prepare($sqlInserir);
    $inserir->execute($dados);
    return (int) $pdo->lastInsertId();
}

try {
    carregarAmbiente(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');
    if (strtolower(getenv('APP_ENV') ?: 'development') === 'production') {
        throw new RuntimeException('O seeder é bloqueado quando APP_ENV=production.');
    }
    $pdo = criarConexao();
    $pdo->beginTransaction();

    $usuarioId = garantirRegistro(
        $pdo,
        'SELECT id_usuario FROM usuario WHERE email = ?',
        ['teste@technopenguin.local'],
        'INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)',
        ['Usuário de Teste', 'teste@technopenguin.local', password_hash('PenguinDev2026!', PASSWORD_BCRYPT)]
    );
    $sequencia = $pdo->prepare('INSERT IGNORE INTO sequencia (id_usuario) VALUES (?)');
    $sequencia->execute([$usuarioId]);

    $cursos = [
        ['HTML e CSS do Zero', 'Aprenda a estruturar e estilizar páginas web.', 'Iniciante', 'HTML', 'CSS'],
        ['JavaScript Essencial', 'Aprenda fundamentos de programação com JavaScript.', 'Iniciante', 'JavaScript', 'JavaScript'],
    ];
    foreach ($cursos as [$tituloCurso, $descricaoCurso, $nivel, $linguagem, $linguagemCodigo]) {
        $cursoId = garantirRegistro(
            $pdo,
            'SELECT id_curso FROM curso WHERE titulo_curso = ?',
            [$tituloCurso],
            'INSERT INTO curso (titulo_curso, descricao_curso, nivel_dificuldade_curso) VALUES (?, ?, ?)',
            [$tituloCurso, $descricaoCurso, $nivel]
        );

        for ($numeroModulo = 1; $numeroModulo <= 2; $numeroModulo++) {
            $tituloModulo = "Módulo {$numeroModulo} — {$linguagem}";
            $moduloId = garantirRegistro(
                $pdo,
                'SELECT id_modulo FROM modulo WHERE id_curso = ? AND sequencia_modulo = ?',
                [$cursoId, $numeroModulo],
                'INSERT INTO modulo (titulo_modulo, sequencia_modulo, id_curso) VALUES (?, ?, ?)',
                [$tituloModulo, $numeroModulo, $cursoId]
            );

            for ($numeroLicao = 1; $numeroLicao <= 2; $numeroLicao++) {
                $tituloLicao = "Lição {$numeroLicao} do módulo {$numeroModulo}";
                $licaoId = garantirRegistro(
                    $pdo,
                    'SELECT id_licao FROM licao WHERE id_modulo = ? AND sequencia_licao = ?',
                    [$moduloId, $numeroLicao],
                    'INSERT INTO licao (titulo_licao, pontos_licao, sequencia_licao, id_modulo) VALUES (?, ?, ?, ?)',
                    [$tituloLicao, 10, $numeroLicao, $moduloId]
                );

                $questoes = [
                    ['multipla_escolha', "Qual é o objetivo principal da {$linguagem} nesta lição?", 'Praticar os conceitos apresentados.'],
                    ['codigo', "Escreva um exemplo simples de {$linguagem}.", 'Exemplo válido conforme os conceitos da lição.'],
                ];
                foreach ($questoes as [$tipo, $enunciado, $gabarito]) {
                    $questaoId = garantirRegistro(
                        $pdo,
                        'SELECT id_questao FROM questao WHERE id_licao = ? AND tipo_questao = ? AND enunciado_questao = ?',
                        [$licaoId, $tipo, $enunciado],
                        'INSERT INTO questao (enunciado_questao, tipo_questao, gabarito_questao, id_licao) VALUES (?, ?, ?, ?)',
                        [$enunciado, $tipo, $gabarito, $licaoId]
                    );
                    if ($tipo === 'codigo') {
                        $exercicio = $pdo->prepare('INSERT IGNORE INTO exercicio (linguagem_exercicio, codigo_base_exercicio, saida_esperada_exercicio, id_questao) VALUES (?, ?, ?, ?)');
                        $exercicio->execute([$linguagemCodigo, '', null, $questaoId]);
                    }
                }
            }
        }
    }

    $conquistas = [
        ['Primeiros passos', 'Conclua sua primeira atividade.'],
        ['Sequência de 3 dias', 'Estude por três dias consecutivos.'],
        ['Primeira lição concluída', 'Conclua sua primeira lição.'],
    ];
    foreach ($conquistas as [$nome, $descricao]) {
        garantirRegistro(
            $pdo,
            'SELECT id_conquista FROM conquista WHERE nome_conquista = ?',
            [$nome],
            'INSERT INTO conquista (nome_conquista, descricao_conquista) VALUES (?, ?)',
            [$nome, $descricao]
        );
    }

    $desafios = [
        ['Complete uma lição nesta semana', 20],
        ['Pratique programação em três dias', 50],
    ];
    foreach ($desafios as [$descricao, $recompensa]) {
        garantirRegistro(
            $pdo,
            'SELECT id_desafio FROM desafio WHERE descricao_desafio = ?',
            [$descricao],
            'INSERT INTO desafio (descricao_desafio, recompensa_xp_desafio, data_expiracao_desafio) VALUES (?, ?, ?)',
            [$descricao, $recompensa, (new DateTimeImmutable('+30 days'))->format('Y-m-d H:i:s')]
        );
    }

    $pdo->commit();
    fwrite(STDOUT, "Seeder de desenvolvimento aplicado sem duplicar os dados existentes.\n");
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, "Erro no seeder: {$e->getMessage()}\n");
    exit(1);
}
