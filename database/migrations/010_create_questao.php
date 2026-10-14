<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE questao (
        id_questao INT UNSIGNED NOT NULL AUTO_INCREMENT,
        enunciado_questao TEXT NOT NULL,
        tipo_questao VARCHAR(30) NOT NULL,
        gabarito_questao TEXT NOT NULL,
        id_licao INT UNSIGNED NOT NULL,
        PRIMARY KEY (id_questao),
        CONSTRAINT fk_questao_licao FOREIGN KEY (id_licao) REFERENCES licao (id_licao) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS questao',
];
