<?php

namespace Application\Controllers\Admin;

use App\Models\PerguntaModelo;
use App\Models\Pergunta;
use App\Services\PerguntaService;
use Application\Core\Request;
use Application\Core\ValidationException;

class PerguntaController extends AdminController
{
    private PerguntaService $perguntaService;

    public function __construct()
    {
        parent::__construct();
        $this->perguntaService = new PerguntaService();
    }

    /**
     * Lista perguntas
     */
    public function index()
    {
        $perguntas = $this->perguntaService->getPerguntasDoAdmin($this->adminId);
        $perguntasModelo = PerguntaModelo::all();

        $this->renderAdmin('admin/perguntas/index', [
            'perguntas' => $perguntas,
            'perguntas_modelo' => $perguntasModelo
        ]);
    }

    /**
     * Salva nova pergunta
     */
    public function salvar()
    {
        $request = new Request();

        try {
            // Valida dados
            $validated = $request->validate([
                'pergunta' => 'required|max_len,255',
                'chave' => 'max_len,100',
                'tipo' => 'required|contains_list,texto;numero;select;radio;checkbox;data;assinatura',
                'placeholder' => 'max_len,255'
            ], [
                'pergunta' => 'trim|sanitize_string',
                'chave' => 'trim|sanitize_string',
                'placeholder' => 'trim|sanitize_string',
                'opcoes' => 'trim'
            ]);

            // Prepara dados
            $data = [
                'admin_id' => $this->adminId,
                'pergunta' => $validated['pergunta'],
                'tipo' => $validated['tipo'],
                'fixa' => $request->has('fixa') ? 1 : 0,
                'obrigatorio' => $request->has('obrigatorio') ? 1 : 0,
                'complemento_texto' => $request->has('complemento_texto') ? 1 : 0,
                'placeholder' => $validated['placeholder'] ?? null,
                'opcoes' => $request->get('opcoes')
            ];

            // Gera ou valida chave
            $data['chave'] = $this->perguntaService->gerarChaveUnica(
                $validated['chave'] ?? '',
                $validated['pergunta'],
                $this->adminId
            );

            // Cria pergunta
            Pergunta::create($data);

            $this->setSuccess('Pergunta criada com sucesso!');
        } catch (ValidationException $e) {
            $this->setError('Erro de validação: ' . implode(', ', $e->getErrors()));
        } catch (\Exception $e) {
            $this->setError('Erro ao salvar pergunta: ' . $e->getMessage());
        }

        $this->redirect('admin/perguntas');
    }

    /**
     * Exclui pergunta
     */
    public function excluir($id)
    {
        $pergunta = Pergunta::where('id', $id)
            ->where('admin_id', $this->adminId)
            ->first();

        if (!$pergunta) {
            $this->setError('Pergunta não encontrada');
            $this->redirect('admin/perguntas');
        }

        try {
            $pergunta->delete();
            $this->setSuccess('Pergunta excluída com sucesso!');
        } catch (\Exception $e) {
            $this->setError('Erro ao excluir pergunta');
        }

        $this->redirect('admin/perguntas');
    }

    /**
     * Usa modelo de pergunta
     */
    public function usarModelo()
    {
        $request = new Request();
        $modeloId = $request->get('modelo_id');

        if (!$modeloId) {
            $this->setError('Modelo não selecionado');
            $this->redirect('admin/perguntas');
        }

        try {
            $this->perguntaService->criarPerguntaDeModelo($modeloId, $this->adminId);
            $this->setSuccess('Pergunta criada a partir do modelo!');
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
        }

        $this->redirect('admin/perguntas');
    }
}
