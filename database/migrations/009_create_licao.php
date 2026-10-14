<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE licao (
        id_licao INT UNSIGNED NOT NULL AUTO_INCREMENT,
        titulo_licao VARCHAR(100) NOT NULL,
        pontos_licao INT UNSIGNED NOT NULL,
        sequencia_licao INT UNSIGNED NOT NULL,
        id_modulo INT UNSIGNED NOT NULL,
        PRIMARY KEY (id_licao),
        UNIQUE KEY uq_licao_modulo_sequencia (id_modulo, sequencia_licao),
        CONSTRAINT fk_licao_modulo FOREIGN KEY (id_modulo) REFERENCES modulo (id_modulo) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS licao',
];
