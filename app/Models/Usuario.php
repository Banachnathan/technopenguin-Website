<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Usuario extends Model
{
    protected const TABLE = 'usuario';
    protected const PRIMARY_KEY = 'id_usuario';
    protected const FILLABLE = ['nome', 'email', 'senha', 'total_progresso_usuario'];

    public function buscarPorEmail(string $email): ?array
    {
        return $this->one('SELECT * FROM usuario WHERE email = ? LIMIT 1', [$email]);
    }

    public function sequencia(int $idUsuario): ?array
    {
        return $this->one('SELECT * FROM sequencia WHERE id_usuario = ? LIMIT 1', [$idUsuario]);
    }

    public function progresso(int $idUsuario): array
    {
        return $this->many('SELECT * FROM progresso WHERE id_usuario = ? ORDER BY id_licao', [$idUsuario]);
    }

    public function conquistas(int $idUsuario): array
    {
        return $this->many('SELECT c.*, uc.data_desbloqueio FROM conquista c INNER JOIN usuario_conquista uc ON uc.id_conquista = c.id_conquista WHERE uc.id_usuario = ? ORDER BY uc.data_desbloqueio DESC', [$idUsuario]);
    }

    public function desafios(int $idUsuario): array
    {
        return $this->many('SELECT d.*, ud.status_participacao FROM desafio d INNER JOIN usuario_desafio ud ON ud.id_desafio = d.id_desafio WHERE ud.id_usuario = ? ORDER BY d.data_expiracao_desafio', [$idUsuario]);
    }
}
