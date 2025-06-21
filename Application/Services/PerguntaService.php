<?php

namespace App\Services;

use App\Models\Pergunta;
use App\Models\PerguntaModelo;

class PerguntaService
{
    /**
     * Busca perguntas do admin (próprias + fixas)
     */
    public function getPerguntasDoAdmin(int $adminId): \Illuminate\Support\Collection
    {
        return Pergunta::where(function ($query) use ($adminId) {
            $query->where('admin_id', $adminId)
                ->orWhere(function ($q) {
                    $q->whereNull('admin_id')->where('fixa', 1);
                });
        })
            ->orderBy('fixa', 'desc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Gera chave única para pergunta
     */
    public function gerarChaveUnica(string $chaveInformada, string $pergunta, int $adminId): string
    {
        // Se foi informada uma chave, valida
        if (!empty($chaveInformada)) {
            $chave = $this->normalizarChave($chaveInformada);

            if ($this->chaveExiste($chave, $adminId)) {
                throw new \Exception("A chave '{$chave}' já está em uso");
            }

            return $chave;
        }

        // Gera chave automaticamente
        $chaveBase = $this->normalizarChave($pergunta);
        $chave = $chaveBase;
        $contador = 2;

        while ($this->chaveExiste($chave, $adminId)) {
            $chave = $chaveBase . '_' . $contador;
            $contador++;
        }

        return $chave;
    }

    /**
     * Normaliza string para chave
     */
    private function normalizarChave(string $texto): string
    {
        // Remove acentos
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);

        // Substitui caracteres especiais por underscore
        $texto = preg_replace('/[^a-zA-Z0-9]+/', '_', $texto);

        // Remove underscores do início e fim
        $texto = trim($texto, '_');

        // Converte para minúsculas
        return strtolower($texto);
    }

    /**
     * Verifica se chave existe para o admin
     */
    private function chaveExiste(string $chave, int $adminId): bool
    {
        return Pergunta::where('admin_id', $adminId)
            ->where('chave', $chave)
            ->exists();
    }

    /**
     * Cria pergunta a partir de modelo
     */
    public function criarPerguntaDeModelo(int $modeloId, int $adminId): Pergunta
    {
        $modelo = PerguntaModelo::find($modeloId);

        if (!$modelo) {
            throw new \Exception('Modelo não encontrado');
        }

        $chave = $this->gerarChaveUnica('', $modelo->pergunta, $adminId);

        return Pergunta::create([
            'admin_id' => $adminId,
            'pergunta' => $modelo->pergunta,
            'chave' => $chave,
            'tipo' => $modelo->tipo,
            'opcoes' => $modelo->opcoes,
            'complemento_texto' => $modelo->complemento_texto,
            'placeholder' => $modelo->placeholder,
            'fixa' => 1,
            'obrigatorio' => 1
        ]);
    }

    /**
     * Valida opções para tipos específicos
     */
    public function validarOpcoes(string $tipo, ?string $opcoes): bool
    {
        $tiposComOpcoes = ['select', 'radio', 'checkbox'];

        if (in_array($tipo, $tiposComOpcoes)) {
            return !empty($opcoes);
        }

        return true;
    }

    /**
     * Formata opções para exibição
     */
    public function formatarOpcoes(?string $opcoes): array
    {
        if (empty($opcoes)) {
            return [];
        }

        // Assume que opções são separadas por quebra de linha
        $linhas = explode("\n", $opcoes);

        return array_map('trim', array_filter($linhas));
    }
}
