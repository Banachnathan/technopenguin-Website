<?php
declare(strict_types=1);

return [
    'up' => "CREATE TABLE progresso (
        id_progresso INT UNSIGNED NOT NULL AUTO_INCREMENT,
        status_progresso VARCHAR(20) NOT NULL DEFAULT 'Em Andamento',
        data_conclusao_progresso DATETIME NULL DEFAULT NULL,
        id_usuario INT UNSIGNED NOT NULL,
        id_licao INT UNSIGNED NOT NULL,
        PRIMARY KEY (id_progresso),
        UNIQUE KEY uq_progresso_usuario_licao (id_usuario, id_licao),
        CONSTRAINT fk_progresso_usuario FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
        CONSTRAINT fk_progresso_licao FOREIGN KEY (id_licao) REFERENCES licao (id_licao) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    'down' => 'DROP TABLE IF EXISTS progresso',
];
