# TechnoPenguin

Plataforma de aprendizado de programação. O repositório contém a landing page, login estático, migrations MySQL e uma base de backend em PHP sem framework.

## Backend

O backend atual oferece models PDO e controllers internos para as 12 tabelas do schema. Ainda não há API HTTP, rotas, autenticação, views nem integração com a interface. Consulte [docs/backend.md](docs/backend.md) para arquitetura, classes e exemplos.

## Banco de dados

Requisitos, configuração do `.env`, migrations, rollback, reset de desenvolvimento e seeder estão em [database/README.md](database/README.md).
