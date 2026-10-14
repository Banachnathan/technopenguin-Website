<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Conquista extends Model
{
    protected const TABLE = 'conquista';
    protected const PRIMARY_KEY = 'id_conquista';
    protected const FILLABLE = ['nome_conquista', 'descricao_conquista'];

    public function deUsuario(int $idUsuario): array
    {
        return $this->many('SELECT c.*, uc.data_desbloqueio FROM conquista c INNER JOIN usuario_conquista uc ON uc.id_conquista = c.id_conquista WHERE uc.id_usuario = ? ORDER BY uc.data_desbloqueio DESC', [$idUsuario]);
    }
}
