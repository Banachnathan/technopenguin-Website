<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE usuario_conquista (
        id_usuario INT UNSIGNED NOT NULL,
        id_conquista INT UNSIGNED NOT NULL,
        data_desbloqueio DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id_usuario, id_conquista),
        CONSTRAINT fk_usuario_conquista_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
        CONSTRAINT fk_usuario_conquista_conquista FOREIGN KEY (id_conquista) REFERENCES conquista (id_conquista) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS usuario_conquista',
];
