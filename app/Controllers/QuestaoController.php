<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Questao;

final class QuestaoController extends ResourceController
{
    protected const MODEL_CLASS = Questao::class;
}
