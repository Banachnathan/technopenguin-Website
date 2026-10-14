# Base do backend TechnoPenguin

## Objetivo e limites

Esta etapa cria uma camada PHP sem framework para persistência e operações de aplicação sobre o schema existente. Ela prepara o projeto para receber uma API ou outra camada de entrada depois que essa decisão for tomada.

Não há rotas, endpoints HTTP, serialização JSON, autenticação, views, Blade, React ou integração com o front-end nesta camada. Os métodos dos controllers são chamadas PHP internas e não devem ser expostos diretamente a clientes. Nenhuma tabela ou migration foi alterada para acomodar código da aplicação.

## Estrutura

```text
app/
  bootstrap.php             Autoload simples para o namespace TechnoPenguin\
  Core/
    Model.php               Operações PDO comuns e proteção de campos graváveis
    ResourceController.php  Operações internas comuns aos controllers
  Models/                   Uma model por tabela do domínio
  Controllers/              Um controller por recurso/relacionamento
database/
  Connection.php            Carregamento de .env e conexão PDO
  migrations/               Schema e ordem de criação
docs/
  backend.md                Este documento
```

O projeto não tem Composer nem framework instalado. O autoload em `app/bootstrap.php` segue o namespace `TechnoPenguin\` e converte o restante do namespace em caminho sob `app/`. A conexão existente é obtida com `criarConexao()` de `database/Connection.php`.

## Mapeamento do domínio

| Tabela              | Model              | Controller                   |
|              ---    |   ---              |        ---                   |
| `usuario`           | `Usuario`          | `UsuarioController`          |
| `curso`             | `Curso`            | `CursoController`            |
| `conquista`         | `Conquista`        | `ConquistaController`        |
| `desafio`           | `Desafio`          | `DesafioController`          |
| `sequencia`         | `Sequencia`        | `SequenciaController`        |
| `modulo`            | `Modulo`           | `ModuloController`           |
| `usuario_conquista` | `UsuarioConquista` | `UsuarioConquistaController` |
| `usuario_desafio`   | `UsuarioDesafio`   | `UsuarioDesafioController`   |
| `licao`             | `Licao`            | `LicaoController`            |
| `questao`           | `Questao`          | `QuestaoController`          |
| `exercicio`         | `Exercicio`        | `ExercicioController`        |
| `progresso`         | `Progresso`        | `ProgressoController`        |

As models de relacionamento também aceitam chaves primárias compostas. Para `UsuarioConquista` e `UsuarioDesafio`, passe a chave como array associativo, por exemplo `['id_usuario' => 4, 'id_conquista' => 2]`.

As models incluem consultas simples para as relações do schema: curso e módulos, módulo e lições, lição e questões, questão e exercício, usuário e sequência/progresso/conquistas/desafios. Elas espelham as FKs atuais; não modelam relações que ainda não existem no banco, como matrícula ou curso escolhido pelo usuário.

## Model e controller base

`TechnoPenguin\Core\Model` oferece `all()`, `find($id)`, `create($dados)`, `update($id, $dados)` e `delete($id)`. As instruções usam prepared statements; cada model declara tabela, chave primária e uma lista `FILLABLE`. Campos fora dessa lista são rejeitados, e nomes SQL não vêm da entrada do usuário. `delete()` retorna `false` quando o registro não existe; restrições de FK continuam sendo aplicadas pelo banco.

`TechnoPenguin\Core\ResourceController` encaminha `index()`, `show($id)`, `store($dados)`, `update($id, $dados)` e `destroy($id)` para sua model. `UsuarioController` acrescenta validação básica de nome/email e gera hash bcrypt da senha recebida em `store` e `update`. Ele não implementa login ou sessão. A aplicação não deve devolver o campo `senha` em uma resposta futura.

## Exemplo de uso interno

Depois de configurar `.env` e aplicar as migrations conforme [database/README.md](../database/README.md):

```php
<?php
require_once __DIR__ . '/../app/bootstrap.php';

use TechnoPenguin\Controllers\CursoController;
use TechnoPenguin\Models\Curso;

$pdo = criarConexao();

$cursos = new CursoController($pdo);
$lista = $cursos->index();

$cursoModel = new Curso($pdo);
$cursoComModulos = $cursoModel->comModulos(1);
```

O exemplo é código de aplicação, não uma rota ou endpoint. Controllers e models recebem `PDO` por injeção, facilitando a composição futura sem acoplar as classes a uma escolha de framework de view.

## Preparação para a próxima fase

Quando a equipe escolher a entrada HTTP, a API poderá adicionar rotas, validação de request, autenticação/autorização, transformação de respostas e tratamento consistente de erros em uma camada própria. Regras ainda não decididas — cálculo da sequência diária e fuso horário, critérios para conquistas, pontuação, regras de matrícula e política de atualização de progresso — devem ser definidas antes de implementar os respectivos casos de uso.

As operações CRUD genéricas não substituem essas regras e não são um contrato de API. A camada ainda não inclui transações de negócio que atravessem várias tabelas, DTOs, paginação, filtros, logging ou política de permissões. Esses pontos devem ser implementados na próxima etapa sem alterar a independência entre domínio e apresentação.
