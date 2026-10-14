<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE usuario (
        id_usuario INT UNSIGNED NOT NULL AUTO_INCREMENT,
        nome VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL,
        senha VARCHAR(60) NOT NULL,
        total_progresso_usuario INT UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY (id_usuario),
        UNIQUE KEY uq_usuario_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS usuario',
];
