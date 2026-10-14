<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE conquista (
        id_conquista INT UNSIGNED NOT NULL AUTO_INCREMENT,
        nome_conquista VARCHAR(50) NOT NULL,
        descricao_conquista VARCHAR(200) NOT NULL,
        PRIMARY KEY (id_conquista)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS conquista',
];
