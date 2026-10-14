<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\UsuarioConquista;

final class UsuarioConquistaController extends ResourceController
{
    protected const MODEL_CLASS = UsuarioConquista::class;
}
