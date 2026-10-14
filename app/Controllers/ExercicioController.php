<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Exercicio;

final class ExercicioController extends ResourceController
{
    protected const MODEL_CLASS = Exercicio::class;
}
