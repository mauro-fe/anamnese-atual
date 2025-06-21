<?php

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

class Router
{
    private static array $routes = [];

    public static function add(string $method, string $path, $callback): void
    {
        $path = trim($path, '/');
        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback,
        ];
    }

    public static function run(string $requestedPath): void
    {
        $requestedPath = trim($requestedPath, '/');
        $requestedPath = $requestedPath === '' ? '/' : $requestedPath;

        foreach (self::$routes as $route) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $route['path']);
            $pattern = "#^" . trim($pattern, '/') . "$#";

            if ($_SERVER['REQUEST_METHOD'] === $route['method'] && preg_match($pattern, $requestedPath, $matches)) {
                array_shift($matches);

                // Middleware: protege rotas admin
                $rotaProtegida = preg_match('#^(admin|admins)/#', $route['path']);
                $rotaPublica = in_array($route['path'], ['admin/login', 'admin/login/process']);

                if ($rotaProtegida && !$rotaPublica) {
                    \AuthMiddleware::adminOnly();
                }

                if (is_callable($route['callback'])) {
                    call_user_func_array($route['callback'], $matches);
                    return;
                }

                // Suporte a controller@metodo
                if (is_string($route['callback'])) {
                    [$controllerPath, $method] = explode('@', $route['callback']);
                    $controllerNamespace = 'Application\\Controllers\\' . str_replace('/', '\\', $controllerPath);

                    try {
                        if (!class_exists($controllerNamespace)) {
                            throw new Exception("Controlador '{$controllerNamespace}' não encontrado.");
                        }

                        $controllerInstance = new $controllerNamespace();

                        if (!method_exists($controllerInstance, $method)) {
                            throw new Exception("Método '{$method}' não encontrado no controlador '{$controllerNamespace}'.");
                        }

                        call_user_func_array([$controllerInstance, $method], $matches);
                        return;
                    } catch (Exception $e) {
                        http_response_code(500);
                        echo "<h2>Erro interno:</h2><p>{$e->getMessage()}</p>";
                        return;
                    }
                }
            }
        }

        http_response_code(404);
        echo "<h2>Página não encontrada</h2>";
    }
}
