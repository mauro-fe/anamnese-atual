<?php

namespace Application\Controllers\Public;

use Application\Controllers\BaseController;
use App\Models\Resposta;
use App\Models\Ficha;
use App\Models\Admin;
use App\Models\Pergunta;
use Application\Core\ValidationException;

class RespostaController extends BaseController
{
    /**
     * Salva respostas da ficha
     */
    public function salvar($clinica = null, $cliente = null)
    {
        try {
            $this->requirePost();

            // Pega dados do formulário
            $respostas = $this->request->get('respostas', []);
            $respostasTexto = $this->request->get('respostas_texto', []);
            $adminId = $this->request->get('admin_id');

            // Validações básicas
            $this->validateBasicData($adminId, $respostas);

            // Valida se admin existe e está ativo
            $admin = $this->validateAdmin($adminId);

            // Cria nova ficha
            $ficha = $this->createFicha($adminId);

            // Salva respostas
            $totalSalvas = $this->saveRespostas($ficha->id, $adminId, $respostas, $respostasTexto);

            // Resposta de sucesso
            if ($this->request->isAjax()) {
                $this->jsonSuccess('Respostas salvas com sucesso!', [
                    'ficha_id' => $ficha->id,
                    'total_respostas' => $totalSalvas,
                    'redirect' => BASE_URL . 'obrigado?clinic=' . urlencode($admin->nome_clinica)
                ]);
            }

            // Redireciona para página de agradecimento
            $this->redirect('obrigado?clinic=' . urlencode($admin->nome_clinica));
        } catch (ValidationException $e) {
            $this->handleError('Erro de validação', $e->getErrors());
        } catch (\Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    /**
     * Valida dados básicos
     */
    private function validateBasicData(?int $adminId, array $respostas): void
    {
        if (!$adminId) {
            throw new \Exception('Dados da clínica não informados.');
        }

        if (empty($respostas) || !is_array($respostas)) {
            throw new \Exception('Nenhuma resposta foi enviada.');
        }
    }

    /**
     * Valida se admin existe e está ativo
     */
    private function validateAdmin(int $adminId): Admin
    {
        $admin = Admin::find($adminId);

        if (!$admin) {
            throw new \Exception('Clínica não encontrada.');
        }

        if (!$admin->ativo) {
            throw new \Exception('Esta clínica não está mais ativa.');
        }

        return $admin;
    }

    /**
     * Cria nova ficha
     */
    private function createFicha(int $adminId): Ficha
    {
        return Ficha::create([
            'admin_id' => $adminId,
            'status' => 'ativa'
        ]);
    }

    /**
     * Salva todas as respostas
     */
    private function saveRespostas(int $fichaId, int $adminId, array $respostas, array $respostasTexto): int
    {
        $contador = 0;

        foreach ($respostas as $perguntaId => $resposta) {
            // Valida se pergunta existe e pertence ao admin
            if (!$this->isValidQuestion($perguntaId, $adminId)) {
                continue;
            }

            // Processa resposta
            $respostaProcessada = $this->processResposta($resposta, $respostasTexto[$perguntaId] ?? null);

            // Pula respostas vazias (exceto para campos obrigatórios)
            if (empty($respostaProcessada)) {
                continue;
            }

            // Salva resposta
            Resposta::create([
                'ficha_id' => $fichaId,
                'pergunta_id' => $perguntaId,
                'admin_id' => $adminId,
                'resposta' => $respostaProcessada
            ]);

            $contador++;
        }

        if ($contador === 0) {
            throw new \Exception('Nenhuma resposta válida foi encontrada.');
        }

        return $contador;
    }

    /**
     * Valida se pergunta é válida para o admin
     */
    private function isValidQuestion(int $perguntaId, int $adminId): bool
    {
        return Pergunta::where('id', $perguntaId)
            ->where(function ($query) use ($adminId) {
                $query->where('admin_id', $adminId)
                    ->orWhere(function ($q) {
                        $q->whereNull('admin_id')->where('fixa', 1);
                    });
            })
            ->exists();
    }

    /**
     * Processa resposta formatando conforme tipo
     */
    private function processResposta($resposta, ?string $complemento): string
    {
        // Se é array (checkbox, select múltiplo), junta com vírgula
        if (is_array($resposta)) {
            $respostaFormatada = implode(', ', array_filter($resposta));
        } else {
            $respostaFormatada = trim($resposta);
        }

        // Adiciona complemento se houver
        if (!empty($complemento)) {
            $respostaFormatada .= ' | Obs: ' . trim($complemento);
        }

        return $respostaFormatada;
    }

    /**
     * Trata erros
     */
    private function handleError(string $message, array $errors = []): void
    {
        if ($this->request->isAjax()) {
            $this->jsonError($message, 400, $errors);
        }

        // Para requisições normais, redireciona com erro
        $_SESSION['error'] = $message;
        $_SESSION['form_data'] = $this->request->all();

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }

    /**
     * Página de agradecimento
     */
    public function obrigado()
    {
        $nomeClinica = $this->request->get('clinic', 'Clínica');

        $data = [
            'nome_clinica' => $nomeClinica,
            'message' => 'Suas respostas foram salvas com sucesso!'
        ];

        $this->render('user/obrigado', $data, 'user/header', 'user/footer');
    }
}
