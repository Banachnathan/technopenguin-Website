<?php
declare(strict_types=1);

namespace TechnoPenguin\Core;

use PDO;
use RuntimeException;

/** Encaminha operações de aplicação para models sem conhecer HTTP ou formato de API. */
abstract class ResourceController
{
    protected const MODEL_CLASS = '';
    protected Model $model;

    public function __construct(PDO $pdo)
    {
        $classeModel = static::MODEL_CLASS;
        if ($classeModel === '' || !is_subclass_of($classeModel, Model::class)) {
            throw new RuntimeException('A model do controller não foi configurada.');
        }
        $this->model = new $classeModel($pdo);
    }

    public function index(): array
    {
        return $this->model->all();
    }

    public function show(int|array $id): array
    {
        return $this->model->find($id) ?? throw new RuntimeException('Registro não encontrado.');
    }

    public function store(array $dados): array
    {
        return $this->model->create($dados);
    }

    public function update(int|array $id, array $dados): array
    {
        return $this->model->update($id, $dados);
    }

    public function destroy(int|array $id): bool
    {
        return $this->model->delete($id);
    }
}
