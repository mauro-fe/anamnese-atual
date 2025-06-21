<?php

namespace Application\Controllers\Admin;

use App\Models\Ficha;
use App\Models\Resposta;
use App\Lib\Helpers;
use App\Services\FichaService;

class DashboardController extends AdminController
{
    private FichaService $fichaService;

    public function __construct()
    {
        parent::__construct();
        $this->fichaService = new FichaService();
    }

    /**
     * Exibe o dashboard do admin
     */
    public function dashboard($username)
    {
        // Valida se o username corresponde ao admin logado
        if ($username !== $this->adminUsername) {
            $this->redirect('admin/' . $this->adminUsername . '/home');
        }

        // Busca fichas do admin
        $fichas = $this->fichaService->getFichasComDadosBasicos($this->adminId);

        // Prepara dados para a view
        $data = [
            'fichas' => $fichas,
            'totalFichas' => count($fichas),
            'ficha_id' => $fichas[0]['id'] ?? 0,
            'slug_clinica' => Helpers::slugify($this->admin->nome_clinica ?? 'clinica')
        ];

        $this->renderAdmin('admin/home/dashboard', $data);
    }

    /**
     * Busca no dashboard
     */
    public function search()
    {
        $request = new \Application\Core\Request();
        $query = $request->get('q', '');

        if (empty($query)) {
            $this->jsonError('Digite algo para buscar');
        }

        $results = $this->fichaService->search($query, $this->adminId);

        $this->jsonSuccess('Busca realizada', [
            'results' => $results,
            'total' => count($results)
        ]);
    }
}
