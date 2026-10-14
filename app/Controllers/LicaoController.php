<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Licao;

final class LicaoController extends ResourceController
{
    protected const MODEL_CLASS = Licao::class;
}
