<?php
declare(strict_types=1);

namespace TechnoPenguin\Core;

use InvalidArgumentException;
use PDO;
use RuntimeException;

abstract class Model
{
    protected const TABLE = '';
    protected const PRIMARY_KEY = '';
    protected const FILLABLE = [];

    public function __construct(protected readonly PDO $pdo)
    {
        if (static::TABLE === '' || static::PRIMARY_KEY === '' || !preg_match('/^[a-z_]+$/', static::TABLE)) {
            throw new RuntimeException('A configuração da model está inválida.');
        }
    }

    public function all(): array
    {
        return $this->many('SELECT * FROM `' . static::TABLE . '`');
    }

    public function find(int|array $id): ?array
    {
        [$where, $valores] = $this->condicaoChave($id);
        return $this->one('SELECT * FROM `' . static::TABLE . '` WHERE ' . $where . ' LIMIT 1', $valores);
    }

    public function create(array $dados): array
    {
        $dados = $this->validarCampos($dados);
        if ($dados === []) {
            throw new InvalidArgumentException('Informe ao menos um campo para criar o registro.');
        }

        $colunas = array_keys($dados);
        $sql = 'INSERT INTO `' . static::TABLE . '` (`' . implode('`, `', $colunas) . '`)' 
            . ' VALUES (' . implode(', ', array_fill(0, count($colunas), '?')) . ')';
        $this->pdo->prepare($sql)->execute(array_values($dados));

        $chave = static::PRIMARY_KEY;
        if (is_array($chave)) {
            $id = [];
            foreach ($chave as $campo) {
                if (!array_key_exists($campo, $dados)) {
                    throw new RuntimeException("A chave {$campo} é necessária para localizar o registro criado.");
                }
                $id[$campo] = $dados[$campo];
            }
        } else {
            $id = (int) $this->pdo->lastInsertId();
        }

        return $this->find($id) ?? throw new RuntimeException('O registro foi inserido, mas não pôde ser recuperado.');
    }

    public function update(int|array $id, array $dados): array
    {
        $dados = $this->validarCampos($dados);
        if ($dados !== []) {
            [$where, $valoresChave] = $this->condicaoChave($id);
            $sets = array_map(static fn (string $campo): string => "`{$campo}` = ?", array_keys($dados));
            $sql = 'UPDATE `' . static::TABLE . '` SET ' . implode(', ', $sets) . ' WHERE ' . $where;
            $this->pdo->prepare($sql)->execute([...array_values($dados), ...$valoresChave]);
        }

        return $this->find($id) ?? throw new RuntimeException('Registro não encontrado.');
    }

    public function delete(int|array $id): bool
    {
        if ($this->find($id) === null) {
            return false;
        }
        [$where, $valores] = $this->condicaoChave($id);
        $sql = 'DELETE FROM `' . static::TABLE . '` WHERE ' . $where;
        return $this->pdo->prepare($sql)->execute($valores);
    }

    protected function many(string $sql, array $valores = []): array
    {
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute($valores);
        return $consulta->fetchAll();
    }

    protected function one(string $sql, array $valores = []): ?array
    {
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute($valores);
        $registro = $consulta->fetch();
        return $registro === false ? null : $registro;
    }

    private function validarCampos(array $dados): array
    {
        $permitidos = static::FILLABLE;
        foreach (array_keys($dados) as $campo) {
            if (!in_array($campo, $permitidos, true)) {
                throw new InvalidArgumentException("Campo não permitido em " . static::TABLE . ": {$campo}");
            }
        }
        return $dados;
    }

    private function condicaoChave(int|array $id): array
    {
        $chave = static::PRIMARY_KEY;
        $chaves = is_array($chave) ? $chave : [$chave];
        if (count($chaves) === 1 && !is_array($id)) {
            return ['`' . $chaves[0] . '` = ?', [$id]];
        }
        if (!is_array($id) || array_diff($chaves, array_keys($id)) !== []) {
            throw new InvalidArgumentException('Informe todos os campos da chave primária.');
        }

        $condicoes = [];
        $valores = [];
        foreach ($chaves as $campo) {
            $condicoes[] = "`{$campo}` = ?";
            $valores[] = $id[$campo];
        }
        return [implode(' AND ', $condicoes), $valores];
    }
}
