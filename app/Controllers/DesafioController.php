<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Desafio;

final class DesafioController extends ResourceController
{
    protected const MODEL_CLASS = Desafio::class;
}
