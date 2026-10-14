<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/database/Connection.php';

spl_autoload_register(static function (string $classe): void {
    $prefixo = 'TechnoPenguin\\';
    if (!str_starts_with($classe, $prefixo)) {
        return;
    }

    $caminho = __DIR__ . DIRECTORY_SEPARATOR
        . str_replace('\\', DIRECTORY_SEPARATOR, substr($classe, strlen($prefixo)))
        . '.php';
    if (is_file($caminho)) {
        require_once $caminho;
    }
});
