<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\UsuarioDesafio;

final class UsuarioDesafioController extends ResourceController
{
    protected const MODEL_CLASS = UsuarioDesafio::class;
}
