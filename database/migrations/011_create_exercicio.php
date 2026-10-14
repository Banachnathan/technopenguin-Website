<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE exercicio (
        id_exercicio INT UNSIGNED NOT NULL AUTO_INCREMENT,
        linguagem_exercicio VARCHAR(30) NOT NULL,
        codigo_base_exercicio TEXT NULL,
        saida_esperada_exercicio TEXT NULL,
        id_questao INT UNSIGNED NOT NULL,
        PRIMARY KEY (id_exercicio),
        UNIQUE KEY uq_exercicio_id_questao (id_questao),
        CONSTRAINT fk_exercicio_questao FOREIGN KEY (id_questao) REFERENCES questao (id_questao) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS exercicio',
];
