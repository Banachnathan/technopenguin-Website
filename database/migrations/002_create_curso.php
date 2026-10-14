<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE curso (
        id_curso INT UNSIGNED NOT NULL AUTO_INCREMENT,
        titulo_curso VARCHAR(100) NOT NULL,
        descricao_curso VARCHAR(400) NOT NULL,
        nivel_dificuldade_curso VARCHAR(20) NOT NULL,
        PRIMARY KEY (id_curso)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS curso',
];
