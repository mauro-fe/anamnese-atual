<?php

/**
 * Front Controller - Ponto de entrada da aplicação
 * 
 * Este arquivo gerencia todas as requisições HTTP e as direciona
 * para os controladores apropriados através do sistema de rotas.
 */

// Define o caminho base da aplicação
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Configurações de ambiente (desenvolvimento/produção)
$environment = getenv('APP_ENV') ?: 'production';

if ($environment === 'development') {
    // Configurações para desenvolvimento
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Configurações para produção
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

    // Define um handler customizado para erros em produção
    set_error_handler(function ($severity, $message, $file, $line) {
        // Log do erro (você pode implementar um sistema de logs aqui)
        error_log("Error: [$severity] $message in $file on line $line");

        // Mostra uma página de erro genérica para o usuário
        if ($severity === E_ERROR || $severity === E_USER_ERROR) {
            http_response_code(500);
            include BASE_PATH . '/views/errors/500.php';
            exit;
        }
    });
}

// Carrega o autoloader do Composer
$autoloadPath = BASE_PATH . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die('Erro: Execute "composer install" para instalar as dependências.');
}
require_once $autoloadPath;

// Carrega as configurações da aplicação
$configPath = BASE_PATH . '/config/config.php';
if (!file_exists($configPath)) {
    die('Erro: Arquivo de configuração não encontrado.');
}
require_once $configPath;

// Inicia a sessão com configurações seguras
if (session_status() === PHP_SESSION_NONE) {
    // Configurações de segurança para sessão
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Lax');

    // Se estiver usando HTTPS, habilita secure cookies
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', 1);
    }

    session_start();
}

// Configura headers de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Carrega as rotas da aplicação
$routesPath = BASE_PATH . '/routes/web.php';
if (!file_exists($routesPath)) {
    die('Erro: Arquivo de rotas não encontrado.');
}
require_once $routesPath;

// Processa a requisição atual
try {
    // Obtém a URI da requisição
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

    // Remove o caminho base da URL
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = str_replace('/index.php', '', dirname($scriptName));

    // Normaliza o basePath
    $basePath = rtrim($basePath, '/');
    if ($basePath === '' || $basePath === '.') {
        $basePath = '';
    }

    // Remove o basePath da URI
    if ($basePath && strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }

    // Parse da URL para remover query string
    $parsedUrl = parse_url($requestUri);
    $route = $parsedUrl['path'] ?? '/';

    // Normaliza a rota
    $route = '/' . trim($route, '/');
    if ($route === '/') {
        $route = '/';
    }

    // Define método HTTP
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    // Executa o roteador
    Router::run($route, $method);
} catch (Exception $e) {
    // Tratamento de exceções
    if ($environment === 'development') {
        // Em desenvolvimento, mostra o erro completo
        echo '<h1>Erro:</h1>';
        echo '<pre>' . $e->getMessage() . '</pre>';
        echo '<h2>Stack Trace:</h2>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        // Em produção, loga o erro e mostra página genérica
        error_log($e->getMessage());
        http_response_code(500);

        // Inclui página de erro 500 se existir
        $errorPage = BASE_PATH . '/views/errors/500.php';
        if (file_exists($errorPage)) {
            include $errorPage;
        } else {
            echo 'Ocorreu um erro interno. Por favor, tente novamente mais tarde.';
        }
    }
}
