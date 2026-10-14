<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Desafio extends Model
{
    protected const TABLE = 'desafio';
    protected const PRIMARY_KEY = 'id_desafio';
    protected const FILLABLE = ['descricao_desafio', 'recompensa_xp_desafio', 'data_expiracao_desafio'];

    public function deUsuario(int $idUsuario): array
    {
        return $this->many('SELECT d.*, ud.status_participacao FROM desafio d INNER JOIN usuario_desafio ud ON ud.id_desafio = d.id_desafio WHERE ud.id_usuario = ? ORDER BY d.data_expiracao_desafio', [$idUsuario]);
    }
}
