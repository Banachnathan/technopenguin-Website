<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Sequencia;

final class SequenciaController extends ResourceController
{
    protected const MODEL_CLASS = Sequencia::class;
}
