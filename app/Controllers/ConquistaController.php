<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Conquista;

final class ConquistaController extends ResourceController
{
    protected const MODEL_CLASS = Conquista::class;
}
