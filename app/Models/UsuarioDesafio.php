<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class UsuarioDesafio extends Model
{
    protected const TABLE = 'usuario_desafio';
    protected const PRIMARY_KEY = ['id_usuario', 'id_desafio'];
    protected const FILLABLE = ['id_usuario', 'id_desafio', 'status_participacao'];

    public function participar(int $idUsuario, int $idDesafio): array
    {
        $existente = $this->find(['id_usuario' => $idUsuario, 'id_desafio' => $idDesafio]);
        return $existente ?? $this->create(['id_usuario' => $idUsuario, 'id_desafio' => $idDesafio]);
    }
}
