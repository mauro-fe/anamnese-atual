<?php

namespace Application\Controllers\Admin;

use App\Models\Ficha;
use App\Services\FichaService;
use App\Lib\Helpers;
use Application\Core\ValidationException;

class FichaController extends AdminController
{
    private FichaService $fichaService;

    public function __construct()
    {
        parent::__construct();
        $this->fichaService = new FichaService();
    }

    /**
     * Lista fichas do admin
     */
    public function index()
    {
        // Busca fichas ativas (sem paginação para evitar erro)
        $fichas = Ficha::doAdmin($this->adminId)
            ->where('status', '!=', 'excluida')
            ->with(['respostas' => function ($query) {
                $query->whereHas('pergunta', function ($q) {
                    $q->whereIn('chave', ['nome_completo', 'telefone', 'email']);
                });
            }])
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limita a 50 registros por página
            ->get();

        $this->renderAdmin('admin/fichas/index', [
            'fichas' => $fichas
        ]);
    }

    /**
     * Visualiza ficha específica
     */
    public function verFicha($clinica, $pessoa, $id)
    {
        $ficha = Ficha::find($id);

        // Valida se existe e pertence ao admin
        if (!$ficha || (isset($ficha->status) && $ficha->status === 'excluida')) {
            $this->setError('Ficha não encontrada');
            $this->redirectToHome();
        }

        $this->requireOwnership($ficha, 'Você não tem permissão para ver esta ficha');

        // Busca dados completos da ficha
        $dadosCompletos = $this->fichaService->getFichaCompleta($id, $this->adminId);

        if (!$dadosCompletos) {
            $this->setError('Não foi possível carregar os dados da ficha');
            $this->redirectToHome();
        }

        $data = [
            'ficha' => $dadosCompletos['ficha'],
            'respostas' => $dadosCompletos['respostas'],
            'nome_clinica' => ucfirst(str_replace('-', ' ', $clinica)),
            'nome_paciente' => ucfirst(str_replace('-', ' ', $pessoa)),
            'porcentagem_preenchimento' => $ficha->getPorcentagemPreenchimento(),
            'ficha_completa' => $ficha->estaCompleta()
        ];

        $this->renderAdmin('admin/fichas/ver', $data);
    }

    /**
     * Exclui ficha (usando status)
     */
    public function excluir($id)
    {
        $ficha = Ficha::find($id);

        if (!$ficha || (isset($ficha->status) && $ficha->status === 'excluida')) {
            $this->jsonError('Ficha não encontrada', 404);
        }

        $this->requireOwnership($ficha);

        try {
            // Se tem método delete customizado (usando status)
            if (method_exists($ficha, 'delete')) {
                $ficha->delete();
            } else {
                // Fallback: usa status diretamente
                $ficha->status = 'excluida';
                $ficha->save();
            }

            $this->jsonSuccess('Ficha excluída com sucesso');
        } catch (\Exception $e) {
            $this->jsonError('Erro ao excluir ficha: ' . $e->getMessage());
        }
    }

    /**
     * Restaura ficha excluída
     */
    public function restaurar($id)
    {
        // Busca ficha incluindo excluídas
        $ficha = Ficha::where('id', $id)->first();

        if (!$ficha) {
            $this->jsonError('Ficha não encontrada', 404);
        }

        $this->requireOwnership($ficha);

        try {
            // Se tem método restore customizado
            if (method_exists($ficha, 'restore')) {
                $ficha->restore();
            } else {
                // Fallback: usa status diretamente
                $ficha->status = 'ativa';
                $ficha->save();
            }

            $this->jsonSuccess('Ficha restaurada com sucesso');
        } catch (\Exception $e) {
            $this->jsonError('Erro ao restaurar ficha: ' . $e->getMessage());
        }
    }

    /**
     * Exporta ficha para PDF (TEMPORARIAMENTE DESABILITADO)
     */
    public function exportarPDF($id)
    {
        $ficha = Ficha::find($id);

        if (!$ficha || (isset($ficha->status) && $ficha->status === 'excluida')) {
            $this->setError('Ficha não encontrada');
            $this->redirectToHome();
        }

        $this->requireOwnership($ficha);

        // TEMPORÁRIO: Redireciona com mensagem de que PDF não está disponível
        $this->setError('Funcionalidade de PDF ainda não implementada. Use a visualização normal da ficha.');
        $this->redirect('admin/ficha/' .
            Helpers::slugify($_SESSION['nome_clinica'] ?? 'clinica') . '/' .
            $ficha->gerarIdentificadorUnico() . '/' . $id);


        // QUANDO IMPLEMENTAR PDF, DESCOMENTE AQUI:
        // try {
        //     $pdf = $this->fichaService->gerarPDF($id, $this->adminId);

        //     $this->response
        //         ->header('Content-Type', 'application/pdf')
        //         ->header('Content-Disposition', 'attachment; filename="ficha-' . $id . '.pdf"')
        //         ->setContent($pdf)
        //         ->send();
        // } catch (\Exception $e) {
        //     $this->setError('Erro ao gerar PDF: ' . $e->getMessage());
        //     $this->redirectToHome();
        // }
    }

    /**
     * Adiciona observação à ficha
     */
    public function adicionarObservacao($id)
    {
        $this->requirePost();

        $ficha = Ficha::find($id);

        if (!$ficha || (isset($ficha->status) && $ficha->status === 'excluida')) {
            $this->jsonError('Ficha não encontrada', 404);
        }

        $this->requireOwnership($ficha);

        try {
            $validated = $this->request->validate([
                'observacoes' => 'required|max_len,1000'
            ], [
                'observacoes' => 'trim|sanitize_string'
            ]);

            $ficha->observacoes = $validated['observacoes'];
            $ficha->save();

            $this->jsonSuccess('Observação adicionada com sucesso');
        } catch (ValidationException $e) {
            $this->jsonError('Erro de validação', 422, $e->getErrors());
        } catch (\Exception $e) {
            $this->jsonError('Erro ao salvar observação: ' . $e->getMessage());
        }
    }

    /**
     * Busca fichas
     */
    public function buscar()
    {
        $query = $this->request->get('q', '');

        if (strlen($query) < 3) {
            $this->jsonError('Digite pelo menos 3 caracteres para buscar');
        }

        try {
            $fichas = $this->fichaService->search($query, $this->adminId);

            $this->jsonSuccess('Busca realizada', [
                'fichas' => $fichas,
                'total' => count($fichas)
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Erro na busca: ' . $e->getMessage());
        }
    }

    /**
     * Lista fichas excluídas (lixeira)
     */
    public function lixeira()
    {
        $fichas = Ficha::doAdmin($this->adminId)
            ->where('status', 'excluida')
            ->with(['respostas' => function ($query) {
                $query->whereHas('pergunta', function ($q) {
                    $q->whereIn('chave', ['nome_completo', 'telefone', 'email']);
                });
            }])
            ->orderBy('updated_at', 'desc')
            ->limit(50) // Limita registros
            ->get();

        $this->renderAdmin('admin/fichas/lixeira', [
            'fichas' => $fichas
        ]);
    }

    /**
     * Busca com paginação manual (se necessário)
     */
    public function indexPaginado()
    {
        $page = (int) $this->request->get('page', 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        // Busca total de registros
        $total = Ficha::doAdmin($this->adminId)
            ->where('status', '!=', 'excluida')
            ->count();

        // Busca registros da página atual
        $fichas = Ficha::doAdmin($this->adminId)
            ->where('status', '!=', 'excluida')
            ->with(['respostas' => function ($query) {
                $query->whereHas('pergunta', function ($q) {
                    $q->whereIn('chave', ['nome_completo', 'telefone', 'email']);
                });
            }])
            ->orderBy('created_at', 'desc')
            ->limit($perPage)
            ->offset($offset)
            ->get();

        // Cria informações de paginação
        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'has_pages' => $total > $perPage,
            'has_more_pages' => $page < ceil($total / $perPage)
        ];

        $this->renderAdmin('admin/fichas/index', [
            'fichas' => $fichas,
            'pagination' => $pagination
        ]);
    }

    /**
     * Redireciona para home do admin
     */
    private function redirectToHome(): void
    {
        $this->redirect('admin/' . $this->adminUsername . '/home');
    }
}
