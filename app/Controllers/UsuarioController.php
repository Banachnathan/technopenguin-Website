<?php
declare(strict_types=1);

namespace TechnoPenguin\Controllers;

use InvalidArgumentException;
use TechnoPenguin\Core\ResourceController;
use TechnoPenguin\Models\Usuario;

final class UsuarioController extends ResourceController
{
    protected const MODEL_CLASS = Usuario::class;

    public function store(array $dados): array
    {
        $this->validarCredenciais($dados, true);
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_BCRYPT);
        return parent::store($dados);
    }

    public function update(int|array $id, array $dados): array
    {
        if (array_key_exists('nome', $dados)) {
            $this->validarNome($dados['nome']);
        }
        if (array_key_exists('senha', $dados)) {
            if (!is_string($dados['senha']) || $dados['senha'] === '') {
                unset($dados['senha']);
            } else {
                $this->validarSenha($dados['senha']);
                $dados['senha'] = password_hash($dados['senha'], PASSWORD_BCRYPT);
            }
        }
        if (array_key_exists('email', $dados)) {
            $this->validarEmail($dados['email']);
        }
        return parent::update($id, $dados);
    }

    private function validarCredenciais(array $dados, bool $exigirSenha): void
    {
        $this->validarNome($dados['nome'] ?? null);
        $this->validarEmail($dados['email'] ?? null);
        if ($exigirSenha) {
            $this->validarSenha($dados['senha'] ?? null);
        }
    }

    private function validarNome(mixed $nome): void
    {
        if (!is_string($nome) || trim($nome) === '' || mb_strlen($nome) > 50) {
            throw new InvalidArgumentException('O nome é obrigatório e deve ter até 50 caracteres.');
        }
    }

    private function validarEmail(mixed $email): void
    {
        if (!is_string($email) || mb_strlen($email) > 50 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Informe um email válido de até 50 caracteres.');
        }
    }

    private function validarSenha(mixed $senha): void
    {
        if (!is_string($senha) || strlen($senha) < 8 || strlen($senha) > 72) {
            throw new InvalidArgumentException('A senha deve ter entre 8 e 72 bytes.');
        }
    }
}
