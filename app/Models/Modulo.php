<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Modulo extends Model
{
    protected const TABLE = 'modulo';
    protected const PRIMARY_KEY = 'id_modulo';
    protected const FILLABLE = ['titulo_modulo', 'sequencia_modulo', 'id_curso'];

    public function licoes(int $idModulo): array
    {
        return $this->many('SELECT * FROM licao WHERE id_modulo = ? ORDER BY sequencia_licao', [$idModulo]);
    }
}
