<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Progresso extends Model
{
    protected const TABLE = 'progresso';
    protected const PRIMARY_KEY = 'id_progresso';
    protected const FILLABLE = ['status_progresso', 'data_conclusao_progresso', 'id_usuario', 'id_licao'];

    public function deUsuario(int $idUsuario): array
    {
        return $this->many('SELECT * FROM progresso WHERE id_usuario = ? ORDER BY id_licao', [$idUsuario]);
    }

    public function daLicao(int $idUsuario, int $idLicao): ?array
    {
        return $this->one('SELECT * FROM progresso WHERE id_usuario = ? AND id_licao = ? LIMIT 1', [$idUsuario, $idLicao]);
    }
}
