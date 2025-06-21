<?php

namespace App\Lib;

use App\Models\Admin;

class Auth
{
    /**
     * Verifica se o admin está logado e autorizado
     */
    public static function checkAdmin($expectedUsername = null)
    {
        self::initSession();

        if (!self::isLoggedIn()) {
            self::redirectToLogin();
        }

        if ($expectedUsername && !self::isAuthorized($expectedUsername)) {
            self::redirectToHome();
        }

        return $_SESSION['admin_id'];
    }

    /**
     * Verifica se está logado
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_logged_in']) &&
            $_SESSION['admin_logged_in'] === true &&
            isset($_SESSION['admin_id']);
    }

    /**
     * Verifica se o username corresponde ao logado
     */
    public static function isAuthorized(string $username): bool
    {
        return isset($_SESSION['admin_username']) &&
            $_SESSION['admin_username'] === $username;
    }

    /**
     * Pega o admin atual
     */
    public static function user(): ?Admin
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        // Cache na sessão para evitar queries desnecessárias
        if (!isset($_SESSION['admin_cache'])) {
            $admin = Admin::find($_SESSION['admin_id']);

            if (!$admin || !$admin->ativo) {
                self::logout();
                return null;
            }

            $_SESSION['admin_cache'] = $admin;
        }

        return $_SESSION['admin_cache'];
    }

    /**
     * Pega o ID do admin atual
     */
    public static function id(): ?int
    {
        return self::isLoggedIn() ? $_SESSION['admin_id'] : null;
    }

    /**
     * Pega o username do admin atual
     */
    public static function username(): ?string
    {
        return self::isLoggedIn() ? $_SESSION['admin_username'] : null;
    }

    /**
     * Faz login do admin
     */
    public static function login(Admin $admin): void
    {
        self::initSession();

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin->id;
        $_SESSION['admin_username'] = $admin->username;
        $_SESSION['nome_clinica'] = $admin->nome_clinica;
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();

        // Limpa cache
        unset($_SESSION['admin_cache']);
    }

    /**
     * Faz logout
     */
    public static function logout(): void
    {
        self::initSession();

        // Limpa todas as variáveis de sessão
        $_SESSION = [];

        // Destrói o cookie de sessão
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destrói a sessão
        session_destroy();
    }

    /**
     * Verifica e atualiza última atividade
     */
    public static function checkActivity(int $timeout = 3600): bool
    {
        if (!self::isLoggedIn()) {
            return false;
        }

        $lastActivity = $_SESSION['last_activity'] ?? 0;
        $elapsed = time() - $lastActivity;

        if ($elapsed > $timeout) {
            self::logout();
            return false;
        }

        $_SESSION['last_activity'] = time();
        return true;
    }

    /**
     * Gera token CSRF
     */
    public static function generateCsrfToken(): string
    {
        self::initSession();

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Valida token CSRF
     */
    public static function validateCsrfToken(string $token): bool
    {
        self::initSession();

        return isset($_SESSION['csrf_token']) &&
            hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Redireciona para login
     */
    private static function redirectToLogin(): void
    {
        header('Location: ' . BASE_URL . 'admin/login');
        exit;
    }

    /**
     * Redireciona para home do admin
     */
    private static function redirectToHome(): void
    {
        $username = $_SESSION['admin_username'] ?? 'admin';
        header('Location: ' . BASE_URL . 'admin/' . $username . '/home');
        exit;
    }

    /**
     * Inicializa sessão se necessário
     */
    private static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
