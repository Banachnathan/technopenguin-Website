<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Curso;

final class CursoController extends ResourceController
{
    protected const MODEL_CLASS = Curso::class;
}
