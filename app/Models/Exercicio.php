<?php
declare(strict_types=1);

namespace TechnoPenguin\Models;

use TechnoPenguin\Core\Model;

final class Exercicio extends Model
{
    protected const TABLE = 'exercicio';
    protected const PRIMARY_KEY = 'id_exercicio';
    protected const FILLABLE = ['linguagem_exercicio', 'codigo_base_exercicio', 'saida_esperada_exercicio', 'id_questao'];

    public function deQuestao(int $idQuestao): ?array
    {
        return $this->one('SELECT * FROM exercicio WHERE id_questao = ? LIMIT 1', [$idQuestao]);
    }
}
