<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Questao extends Model
{
    protected const TABLE = 'questao';
    protected const PRIMARY_KEY = 'id_questao';
    protected const FILLABLE = ['enunciado_questao', 'tipo_questao', 'gabarito_questao', 'id_licao'];

    public function exercicio(int $idQuestao): ?array
    {
        return $this->one('SELECT * FROM exercicio WHERE id_questao = ? LIMIT 1', [$idQuestao]);
    }
}
