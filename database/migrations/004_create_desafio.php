<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE desafio (
        id_desafio INT UNSIGNED NOT NULL AUTO_INCREMENT,
        descricao_desafio VARCHAR(150) NOT NULL,
        recompensa_xp_desafio INT UNSIGNED NOT NULL,
        data_expiracao_desafio DATETIME NOT NULL,
        PRIMARY KEY (id_desafio)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS desafio',
];
