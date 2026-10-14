<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class UsuarioConquista extends Model
{
    protected const TABLE = 'usuario_conquista';
    protected const PRIMARY_KEY = ['id_usuario', 'id_conquista'];
    protected const FILLABLE = ['id_usuario', 'id_conquista', 'data_desbloqueio'];

    public function desbloquear(int $idUsuario, int $idConquista): array
    {
        $existente = $this->find(['id_usuario' => $idUsuario, 'id_conquista' => $idConquista]);
        return $existente ?? $this->create(['id_usuario' => $idUsuario, 'id_conquista' => $idConquista]);
    }
}
