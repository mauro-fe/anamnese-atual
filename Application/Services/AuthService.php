<?php

namespace App\Services;

use App\Models\Admin;
use App\Lib\Auth;
use Application\Core\Request;
use Application\Core\ValidationException;

class AuthService
{
    private const SESSION_TIMEOUT = 3600; // 1 hora

    /**
     * Realiza login do admin
     */
    public function login(string $login, string $password): array
    {
        // Valida entrada
        if (empty($login) || empty($password)) {
            throw new ValidationException(['login' => 'Preencha todos os campos']);
        }

        // Busca admin por username ou email
        $admin = Admin::where('username', $login)
            ->orWhere('email', $login)
            ->first();

        // Valida credenciais
        if (!$admin || !password_verify($password, $admin->password)) {
            $this->logFailedAttempt($login);
            throw new \Exception('Credenciais inválidas');
        }

        // Verifica se admin está ativo
        if (!$admin->ativo) {
            throw new \Exception('Conta desativada');
        }

        // Registra login bem-sucedido
        Auth::login($admin);
        $this->logSuccessfulLogin($admin);
        session_regenerate_id(true);

        return [
            'admin' => $admin,
            'redirect' => BASE_URL . 'admin/' . $admin->username . '/home'
        ];
    }

    /**
     * Realiza logout
     */
    public function logout(): void
    {
        $adminId = Auth::id();

        if ($adminId) {
            $this->logLogout($adminId);
        }

        Auth::logout();
    }

    /**
     * Verifica se está autenticado
     */
    public function check(): bool
    {
        return Auth::checkActivity(self::SESSION_TIMEOUT);
    }

    /**
     * Pega admin atual
     */
    public function user(): ?Admin
    {
        return Auth::user();
    }

    /**
     * Atualiza senha
     */
    public function updatePassword(int $adminId, string $currentPassword, string $newPassword): void
    {
        $admin = Admin::find($adminId);

        if (!$admin) {
            throw new \Exception('Admin não encontrado');
        }

        // Verifica senha atual
        if (!password_verify($currentPassword, $admin->password)) {
            throw new \Exception('Senha atual incorreta');
        }

        // Valida nova senha
        $this->validatePassword($newPassword);

        // Verifica se não é igual às últimas senhas
        if ($this->isRecentPassword($admin, $newPassword)) {
            throw new \Exception('A nova senha deve ser diferente das últimas utilizadas');
        }

        // Atualiza senhas antigas
        $admin->old_password_2 = $admin->old_password_1 ?? null;
        $admin->old_password_1 = $admin->password;
        $admin->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $admin->password_changed_at = date('Y-m-d H:i:s');

        $admin->save();
    }

    /**
     * Valida senha
     */
    private function validatePassword(string $password): void
    {
        if (strlen($password) < 8) {
            throw new ValidationException(['password' => 'A senha deve ter no mínimo 8 caracteres']);
        }

        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.]).{8,}$/';

        if (!preg_match($pattern, $password)) {
            throw new ValidationException([
                'password' => 'A senha deve conter: 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial'
            ]);
        }
    }

    /**
     * Verifica se é senha recente
     */
    private function isRecentPassword(Admin $admin, string $password): bool
    {
        $passwords = array_filter([
            $admin->password,
            $admin->old_password_1,
            $admin->old_password_2
        ]);

        foreach ($passwords as $oldPassword) {
            if (password_verify($password, $oldPassword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Registra tentativa falha
     */
    private function logFailedAttempt(string $login): void
    {
        // TODO: Implementar log de tentativas falhas
        // Pode usar Monolog ou sistema próprio
    }

    /**
     * Registra login bem-sucedido
     */
    private function logSuccessfulLogin(Admin $admin): void
    {
        $admin->ultimo_login = date('Y-m-d H:i:s');
        $admin->save();

        // TODO: Registrar em log
    }

    /**
     * Registra logout
     */
    private function logLogout(int $adminId): void
    {
        // TODO: Registrar logout em log
    }
}
