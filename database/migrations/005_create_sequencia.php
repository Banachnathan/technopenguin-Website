<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE sequencia (
        id_sequencia INT UNSIGNED NOT NULL AUTO_INCREMENT,
        dias_consecutivos_sequencia INT UNSIGNED NOT NULL DEFAULT 0,
        ultima_atividade_sequencia DATETIME NULL DEFAULT NULL,
        id_usuario INT UNSIGNED NOT NULL,
        PRIMARY KEY (id_sequencia),
        UNIQUE KEY uq_sequencia_id_usuario (id_usuario),
        CONSTRAINT fk_sequencia_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS sequencia',
];
