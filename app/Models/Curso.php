<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Curso extends Model
{
    protected const TABLE = 'curso';
    protected const PRIMARY_KEY = 'id_curso';
    protected const FILLABLE = ['titulo_curso', 'descricao_curso', 'nivel_dificuldade_curso'];

    public function modulos(int $idCurso): array
    {
        return $this->many('SELECT * FROM modulo WHERE id_curso = ? ORDER BY sequencia_modulo', [$idCurso]);
    }

    public function comModulos(int $idCurso): ?array
    {
        $curso = $this->find($idCurso);
        if ($curso !== null) {
            $curso['modulos'] = $this->modulos($idCurso);
        }
        return $curso;
    }
}
