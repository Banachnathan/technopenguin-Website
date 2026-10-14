<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Licao extends Model
{
    protected const TABLE = 'licao';
    protected const PRIMARY_KEY = 'id_licao';
    protected const FILLABLE = ['titulo_licao', 'pontos_licao', 'sequencia_licao', 'id_modulo'];

    public function questoes(int $idLicao): array
    {
        return $this->many('SELECT * FROM questao WHERE id_licao = ? ORDER BY id_questao', [$idLicao]);
    }
}
