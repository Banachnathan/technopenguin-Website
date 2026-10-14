<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Sequencia extends Model
{
    protected const TABLE = 'sequencia';
    protected const PRIMARY_KEY = 'id_sequencia';
    protected const FILLABLE = ['dias_consecutivos_sequencia', 'ultima_atividade_sequencia', 'id_usuario'];

    public function deUsuario(int $idUsuario): ?array
    {
        return $this->one('SELECT * FROM sequencia WHERE id_usuario = ? LIMIT 1', [$idUsuario]);
    }
}
