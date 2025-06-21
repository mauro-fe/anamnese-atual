<?php

namespace Application\Controllers\Admin;

use Application\Controllers\BaseController;
use App\Models\Admin;

abstract class AdminController extends BaseController
{
    protected Admin $admin;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->loadAdmin();
    }

    /**
     * Carrega o admin atual
     */
    protected function loadAdmin(): void
    {
        if ($this->adminId) {
            $this->admin = Admin::find($this->adminId);

            if (!$this->admin) {
                $this->setError('Administrador não encontrado');
                $this->redirect('admin/login');
            }
        }
    }

    /**
     * Verifica se o admin é dono de um recurso
     */
    protected function checkOwnership($resource, string $adminField = 'admin_id'): bool
    {
        return $resource && $resource->$adminField == $this->adminId;
    }

    /**
     * Valida propriedade ou redireciona
     */
    protected function requireOwnership($resource, string $errorMessage = 'Acesso negado'): void
    {
        if (!$this->checkOwnership($resource)) {
            $this->setError($errorMessage);
            $this->redirect('admin/' . $this->adminUsername . '/home');
        }
    }

    /**
     * Renderiza view admin com dados compartilhados
     */
    protected function renderAdmin(string $viewPath, array $data = []): void
    {
        $sharedData = [
            'admin' => $this->admin,
            'adminId' => $this->adminId,
            'adminUsername' => $this->adminUsername,
            'error' => $this->getError(),
            'success' => $this->getSuccess()
        ];

        parent::renderAdmin($viewPath, array_merge($sharedData, $data));
    }
}
