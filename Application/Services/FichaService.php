<?php

namespace App\Services;

use App\Models\Ficha;
use App\Models\Resposta;
use App\Models\Admin;
use App\Lib\Helpers;

class FichaService
{
    /**
     * Busca fichas com dados básicos formatados
     */
    public function getFichasComDadosBasicos(int $adminId): array
    {
        $fichasIds = Ficha::where('admin_id', $adminId)
            ->where('status', '!=', 'excluida') // Evita fichas excluídas
            ->pluck('id')
            ->toArray();

        if (empty($fichasIds)) {
            return [];
        }

        $respostas = $this->getRespostasAgrupadas($fichasIds);

        return $this->formatarFichas($respostas, $adminId);
    }

    /**
     * Busca respostas agrupadas por ficha
     */
    private function getRespostasAgrupadas(array $fichasIds): \Illuminate\Support\Collection
    {
        return Resposta::whereIn('ficha_id', $fichasIds)
            ->join('perguntas', 'respostas.pergunta_id', '=', 'perguntas.id')
            ->select('respostas.*', 'perguntas.chave as pergunta_chave', 'perguntas.pergunta')
            ->get()
            ->groupBy('ficha_id');
    }

    /**
     * Formata fichas com dados básicos
     */
    private function formatarFichas($respostasAgrupadas, int $adminId): array
    {
        $fichas = [];
        $camposBasicos = ['nome_completo', 'telefone', 'sexo', 'idade'];

        foreach ($respostasAgrupadas as $fichaId => $respostasFicha) {
            $fichaData = [
                'id' => $fichaId,
                'admin_id' => $adminId,
                'data_criacao' => null
            ];

            // Inicializa campos básicos
            foreach ($camposBasicos as $campo) {
                $fichaData[$campo] = '';
            }

            // Preenche com respostas
            foreach ($respostasFicha as $resposta) {
                if (in_array($resposta->pergunta_chave, $camposBasicos)) {
                    $fichaData[$resposta->pergunta_chave] = $resposta->resposta;
                }

                // Pega data de criação da primeira resposta
                if (!$fichaData['data_criacao']) {
                    $fichaData['data_criacao'] = $resposta->created_at;
                }
            }

            // Adiciona slugs
            $fichaData['slug_clinica'] = Helpers::slugify($_SESSION['nome_clinica'] ?? 'clinica');
            $fichaData['slug_cliente'] = Helpers::slugify($fichaData['nome_completo'] ?: 'cliente');

            $fichas[] = $fichaData;
        }

        // Ordena por data de criação (mais recente primeiro)
        usort($fichas, function ($a, $b) {
            return strtotime($b['data_criacao']) - strtotime($a['data_criacao']);
        });

        return $fichas;
    }

    /**
     * Busca fichas por termo
     */
    public function search(string $query, int $adminId): array
    {
        $query = '%' . strtolower($query) . '%';

        $fichasIds = Ficha::where('admin_id', $adminId)
            ->where('status', '!=', 'excluida')
            ->pluck('id')
            ->toArray();

        if (empty($fichasIds)) {
            return [];
        }

        // Busca em respostas
        $respostas = Resposta::whereIn('ficha_id', $fichasIds)
            ->where(function ($q) use ($query) {
                $q->whereRaw('LOWER(resposta) LIKE ?', [$query]);
            })
            ->join('perguntas', 'respostas.pergunta_id', '=', 'perguntas.id')
            ->select('respostas.ficha_id', 'respostas.resposta', 'perguntas.pergunta')
            ->distinct()
            ->get();

        // Agrupa resultados
        $resultados = [];
        foreach ($respostas as $resposta) {
            if (!isset($resultados[$resposta->ficha_id])) {
                $resultados[$resposta->ficha_id] = [
                    'ficha_id' => $resposta->ficha_id,
                    'matches' => []
                ];
            }

            $resultados[$resposta->ficha_id]['matches'][] = [
                'pergunta' => $resposta->pergunta,
                'resposta' => $resposta->resposta
            ];
        }

        return array_values($resultados);
    }

    /**
     * Busca ficha completa com todas as respostas
     */
    public function getFichaCompleta(int $fichaId, int $adminId): ?array
    {
        $ficha = Ficha::where('id', $fichaId)
            ->where('admin_id', $adminId)
            ->where('status', '!=', 'excluida')
            ->first();

        if (!$ficha) {
            return null;
        }

        $respostas = Resposta::where('respostas.ficha_id', $fichaId)
            ->where('respostas.admin_id', $adminId)
            ->join('perguntas', 'respostas.pergunta_id', '=', 'perguntas.id')
            ->select('respostas.*', 'perguntas.pergunta', 'perguntas.tipo', 'perguntas.chave')
            ->orderBy('respostas.id')
            ->get();

        return [
            'ficha' => $ficha,
            'respostas' => $respostas,
            'total_respostas' => $respostas->count()
        ];
    }

    /**
     * Gera PDF da ficha
     */
    public function gerarPDF(int $fichaId, int $adminId): string
    {
        // Busca dados completos
        $dados = $this->getFichaCompleta($fichaId, $adminId);

        if (!$dados) {
            throw new \Exception('Ficha não encontrada');
        }

        $ficha = $dados['ficha'];
        $respostas = $dados['respostas'];

        // Busca dados do admin
        $admin = Admin::find($adminId);
        if (!$admin) {
            throw new \Exception('Admin não encontrado');
        }

        // Verifica se tem biblioteca PDF instalada
        if (class_exists('\TCPDF')) {
            return $this->gerarPDFComTCPDF($ficha, $respostas, $admin);
        } elseif (class_exists('\Dompdf\Dompdf')) {
            return $this->gerarPDFComDompdf($ficha, $respostas, $admin);
        } else {
            // Fallback: gera HTML simples
            return $this->gerarHTMLParaDownload($ficha, $respostas, $admin);
        }
    }

    /**
     * Gera PDF usando TCPDF
     */
    private function gerarPDFComTCPDF($ficha, $respostas, $admin): string
    {
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Configurações
        $pdf->SetCreator('Sistema de Anamnese');
        $pdf->SetAuthor($admin->nome_clinica);
        $pdf->SetTitle('Ficha de Anamnese - ' . ($ficha->nome_completo ?? 'Paciente'));

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        // Cabeçalho
        $html = $this->montarHTMLPDF($ficha, $respostas, $admin);

        $pdf->writeHTML($html, true, false, true, false, '');

        return $pdf->Output('ficha.pdf', 'S');
    }

    /**
     * Gera PDF usando DomPDF
     */
    private function gerarPDFComDompdf($ficha, $respostas, $admin): string
    {
        $dompdf = new \Dompdf\Dompdf();

        $html = $this->montarHTMLPDF($ficha, $respostas, $admin);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Fallback: gera HTML para download
     */
    private function gerarHTMLParaDownload($ficha, $respostas, $admin): string
    {
        $html = $this->montarHTMLPDF($ficha, $respostas, $admin);

        // Headers para download como HTML
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="ficha-' . $ficha->id . '.html"');

        return $html;
    }

    /**
     * Monta HTML para PDF
     */
    private function montarHTMLPDF($ficha, $respostas, $admin): string
    {
        $html = '
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
            .clinica { font-size: 18px; font-weight: bold; color: #2c5aa0; }
            .titulo { font-size: 16px; margin: 20px 0; }
            .resposta { margin: 15px 0; page-break-inside: avoid; }
            .pergunta { font-weight: bold; color: #333; margin-bottom: 5px; }
            .resposta-texto { margin-left: 10px; padding: 8px; background-color: #f8f9fa; border-left: 3px solid #2c5aa0; }
            .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
        </style>

        <div class="header">
            <div class="clinica">' . htmlspecialchars($admin->nome_clinica) . '</div>
            <div>' . htmlspecialchars($admin->razao_social ?? '') . '</div>
        </div>

        <h2 class="titulo">Ficha de Anamnese</h2>
        
        <div class="resposta">
            <div class="pergunta">Data de Preenchimento:</div>
            <div class="resposta-texto">' . $ficha->created_at->format('d/m/Y H:i') . '</div>
        </div>';

        foreach ($respostas as $resposta) {
            $html .= '
            <div class="resposta">
                <div class="pergunta">' . htmlspecialchars($resposta->pergunta) . '</div>
                <div class="resposta-texto">' . nl2br(htmlspecialchars($resposta->resposta)) . '</div>
            </div>';
        }

        if ($ficha->observacoes) {
            $html .= '
            <div class="resposta">
                <div class="pergunta">Observações:</div>
                <div class="resposta-texto">' . nl2br(htmlspecialchars($ficha->observacoes)) . '</div>
            </div>';
        }

        $html .= '
        <div class="footer">
            <p>Documento gerado em ' . date('d/m/Y H:i') . ' pelo Sistema de Anamnese</p>
            <p>' . htmlspecialchars($admin->nome_clinica) . '</p>
        </div>';

        return $html;
    }
}
