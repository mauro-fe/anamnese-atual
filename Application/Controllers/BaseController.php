<?php

namespace Application\Controllers;

require_once __DIR__ . '/../../core/View.php';


use View;
use App\Lib\Auth;
use Application\Core\Response;
use Application\Core\Request;

abstract class BaseController
{
    protected $view;
    protected $auth;
    protected $adminId;
    protected $adminUsername;
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->initSession();
        $this->auth = new Auth();
        $this->request = new Request();
        $this->response = new Response();
    }

    /**
     * Inicializa a sessão se necessário
     */
    protected function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica se o admin está logado
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('admin/login');
        }

        $this->adminId = $_SESSION['admin_id'] ?? null;
        $this->adminUsername = $_SESSION['admin_username'] ?? null;
    }

    /**
     * Verifica se está autenticado
     */
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    /**
     * Renderiza uma view
     */
    protected function render(string $viewPath, array $data = [], ?string $header = null, ?string $footer = null): void
    {
        $view = new View($viewPath, $data);

        if ($header) {
            $view->setHeader($header);
        }

        if ($footer) {
            $view->setFooter($footer);
        }

        $view->render();
    }

    /**
     * Renderiza view do admin com header e footer padrão
     */
    protected function renderAdmin(string $viewPath, array $data = []): void
    {
        $this->render($viewPath, $data, 'admin/home/header', 'admin/footer');
    }

    /**
     * Redireciona para uma URL
     */
    protected function redirect(string $path): void
    {
        $url = BASE_URL . ltrim($path, '/');
        $this->response->redirect($url)->send();
    }

    /**
     * Retorna resposta JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        $this->response->json($data, $statusCode)->send();
    }

    /**
     * Retorna erro JSON
     */
    protected function jsonError(string $message, int $statusCode = 400, array $errors = []): void
    {
        Response::error($message, $statusCode, $errors);
    }

    /**
     * Retorna sucesso JSON
     */
    protected function jsonSuccess(string $message, array $data = []): void
    {
        Response::success($data, $message);
    }

    /**
     * Pega dados do POST com sanitização
     */
    protected function getPost(string $key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * Pega dados do GET com sanitização
     */
    protected function getQuery(string $key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * Sanitiza entrada
     */
    protected function sanitize($value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida se a requisição é POST
     */
    protected function requirePost(): void
    {
        if (!$this->request->isPost()) {
            $this->jsonError('Método não permitido', 405);
        }
    }

    /**
     * Define mensagem de erro na sessão
     */
    protected function setError(string $message): void
    {
        $_SESSION['error'] = $message;
    }

    /**
     * Define mensagem de sucesso na sessão
     */
    protected function setSuccess(string $message): void
    {
        $_SESSION['success'] = $message;
    }

    /**
     * Pega e limpa mensagem de erro da sessão
     */
    protected function getError(): ?string
    {
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        return $error;
    }

    /**
     * Pega e limpa mensagem de sucesso da sessão
     */
    protected function getSuccess(): ?string
    {
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        return $success;
    }
}
