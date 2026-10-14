<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE usuario_desafio (
        id_usuario INT UNSIGNED NOT NULL,
        id_desafio INT UNSIGNED NOT NULL,
        status_participacao VARCHAR(20) NULL DEFAULT NULL,
        PRIMARY KEY (id_usuario, id_desafio),
        CONSTRAINT fk_usuario_desafio_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
        CONSTRAINT fk_usuario_desafio_desafio FOREIGN KEY (id_desafio) REFERENCES desafio (id_desafio) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS usuario_desafio',
];
