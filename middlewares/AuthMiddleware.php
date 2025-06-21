<?php

require_once __DIR__ . '/../config/config.php';

class AuthMiddleware
{
    public static function adminOnly()
    {
        // Inicia sessão se necessário
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lista de rotas públicas
        $publicRoutes = [
            'admin/login',
            'admin/login/process',
            'admins/showRegisterForm',
            'admins/processRegister',
            'about',
            'obrigado'
        ];

        // Rota atual acessada
        $currentRoute = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Remove a BASE_URL se for prefixada
        $base = trim(parse_url(BASE_URL, PHP_URL_PATH), '/');
        if ($base && str_starts_with($currentRoute, $base)) {
            $currentRoute = substr($currentRoute, strlen($base));
        }

        // Se a rota atual estiver na lista pública, ignora proteção
        foreach ($publicRoutes as $publicRoute) {
            if (str_starts_with($currentRoute, $publicRoute)) {
                return;
            }
        }

        // Se não estiver logado, redireciona
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit;
        }
    }
}
