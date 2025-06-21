<?php

namespace Application\Controllers\Auth;

use Application\Controllers\BaseController;
use App\Services\AuthService;
use Application\Core\ValidationException;

class LoginController extends BaseController
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    /**
     * Exibe formulário de login
     */
    public function login()
    {
        // Se já está logado, redireciona
        if ($this->isAuthenticated()) {
            $username = $_SESSION['admin_username'] ?? 'admin';
            $this->redirect('admin/' . $username . '/home'); // CORRIGIDO: adicionado /
        }

        $data = [
            'error' => $this->getError(),
            'success' => $this->getSuccess()
        ];

        $this->render('admin/admins/login', $data, 'admin/header', 'admin/footer');
    }

    /**
     * Processa login
     */
    public function processLogin()
    {
        try {
            $this->requirePost();

            // Valida dados
            $validated = $this->request->validate([
                'login' => 'required|max_len,100',
                'password' => 'required'
            ], [
                'login' => 'trim|sanitize_string',
                'password' => 'trim'
            ]);

            // Tenta fazer login
            $result = $this->authService->login(
                $validated['login'],
                $validated['password']
            );

            // Se é requisição AJAX
            if ($this->request->isAjax()) {
                $this->jsonSuccess('Login realizado com sucesso!', [
                    'redirect' => $result['redirect']
                ]);
            }

            // Redireciona
            $this->redirect($result['redirect']);
        } catch (ValidationException $e) {
            $this->handleLoginError('Preencha todos os campos corretamente', $e->getErrors());
        } catch (\Exception $e) {
            $this->handleLoginError($e->getMessage());
        }
    }

    /**
     * Realiza logout
     */
    public function logout()
    {
        $this->authService->logout();
        $this->setSuccess('Logout realizado com sucesso');
        $this->redirect('admin/login');
    }

    /**
     * Exibe formulário de alteração de credenciais
     */
    public function editCredentials($username)
    {
        $this->requireAuth();

        // Valida se é o próprio usuário
        if ($username !== $this->adminUsername) {
            $this->setError('Acesso negado');
            $this->redirect('admin/' . $this->adminUsername . '/home');
        }

        $this->renderAdmin('admin/admins/editCredentials');
    }

    /**
     * Atualiza credenciais
     */
    public function updateCredentials()
    {
        try {
            $this->requireAuth();
            $this->requirePost();

            // Valida dados
            $validated = $this->request->validate([
                'current_password' => 'required',
                'password' => 'required|min_len,8',
                'confirm_password' => 'required|equalsfield,password'
            ], [
                'current_password' => 'trim',
                'password' => 'trim',
                'confirm_password' => 'trim'
            ]);

            // Validação adicional de senha forte
            $this->validateStrongPassword($validated['password']);

            // Atualiza senha
            $this->authService->updatePassword(
                $this->adminId,
                $validated['current_password'],
                $validated['password']
            );

            $this->jsonSuccess('Senha atualizada com sucesso!', [
                'redirect' => BASE_URL . 'admin/' . $this->adminUsername . 'home'
            ]);
        } catch (ValidationException $e) {
            $this->jsonError('Erro de validação', 422, $e->getErrors());
        } catch (\Exception $e) {
            $this->jsonError($e->getMessage());
        }
    }

    /**
     * Valida senha forte
     */
    private function validateStrongPassword(string $password): void
    {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.]).{8,}$/';

        if (!preg_match($pattern, $password)) {
            throw new ValidationException([
                'password' => 'A senha deve conter: 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial'
            ]);
        }
    }

    /**
     * Trata erro de login
     */
    private function handleLoginError(string $message, array $errors = []): void
    {
        if ($this->request->isAjax()) {
            $this->jsonError($message, 400, $errors);
        }

        $this->setError($message);
        $this->redirect('admin/login');
    }
}
