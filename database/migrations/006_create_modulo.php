<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE modulo (
        id_modulo INT UNSIGNED NOT NULL AUTO_INCREMENT,
        titulo_modulo VARCHAR(100) NOT NULL,
        sequencia_modulo INT UNSIGNED NOT NULL,
        id_curso INT UNSIGNED NOT NULL,
        PRIMARY KEY (id_modulo),
        UNIQUE KEY uq_modulo_curso_sequencia (id_curso, sequencia_modulo),
        CONSTRAINT fk_modulo_curso FOREIGN KEY (id_curso) REFERENCES curso (id_curso) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS modulo',
];
