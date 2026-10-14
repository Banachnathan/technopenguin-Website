# Banco de dados TechnoPenguin

Este projeto usa migrations próprias em PHP com PDO e MySQL 8 (compatível com MariaDB 10.6+). Requer PHP 8.1 ou superior com `pdo_mysql` habilitado. As migrations criam as tabelas do modelo; elas não criam o schema/database.

A camada de models e controllers PHP está documentada em [docs/backend.md](../docs/backend.md). Ela ainda não expõe endpoints nem depende de uma tecnologia de views.

## Configuração

1. Crie o banco manualmente em MySQL/MariaDB: `CREATE DATABASE technopenguin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
2. Copie `.env.example` para `.env` e informe host, porta, banco, usuário e senha. Não versione o `.env` real.
3. Confira que `APP_ENV=development` está definido para desenvolvimento.

O loader lê `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD` do `.env`. A conexão usa PDO, exceções, prepared statements nativos e `utf8mb4`.

## Migrations

Na raiz do projeto, execute:

```sh
php database/migrate.php up
php database/migrate.php status
```

`up` aplica as migrations pendentes; `status` lista as 12 migrations como aplicadas ou pendentes. O controle é armazenado em `schema_migrations`. Cada chamada de `up` forma um lote. Para reverter o último lote:

```sh
php database/migrate.php down
```

Para apagar e recriar as tabelas gerenciadas por migrations em desenvolvimento, use `fresh` e digite `FRESH` quando solicitado:

```sh
php database/migrate.php fresh
```

`fresh` é bloqueado se `APP_ENV=production`. O reset remove os dados dessas tabelas.

## Seeder de desenvolvimento

Depois de aplicar as migrations, rode:

```sh
php database/seed.php
```

O seeder é idempotente e bloqueado em produção. Cria um usuário `teste@technopenguin.local`, dois cursos, conteúdo mínimo de módulos/lições/questões/exercícios, três conquistas e dois desafios. A senha de desenvolvimento é `PenguinDev2026!`; ela é armazenada com hash bcrypt gerado por `password_hash` e não deve ser usada fora do ambiente local.

## Notas

O MySQL confirma DDL implicitamente. Por isso, cada migration contém uma única criação/remoção de tabela e o runner registra a execução após o DDL. Em caso de interrupção entre essas operações, confira o estado da tabela e `schema_migrations` antes de continuar.
