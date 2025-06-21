<?php

namespace Application\Controllers\Public;

use Application\Controllers\BaseController;
use App\Models\Pergunta;
use App\Models\Admin;
use App\Models\Ficha;
use App\Lib\Helpers;

class ResponderController extends BaseController
{
    /**
     * Exibe formulário para responder ficha da clínica
     */
    public function responder($slugClinica)
    {
        try {
            // Busca admin pela clínica
            $admin = $this->findAdminByClinicSlug($slugClinica);

            if (!$admin) {
                $this->render('errors/404', [
                    'message' => 'Clínica não encontrada.'
                ]);
                return;
            }

            // Busca perguntas da clínica
            $perguntas = $this->getPerguntasClinica($admin->id);

            if ($perguntas->isEmpty()) {
                $this->render('errors/404', [
                    'message' => 'Nenhuma pergunta encontrada para esta clínica.'
                ]);
                return;
            }

            // Dados para a view
            $data = [
                'perguntas' => $perguntas,
                'admin_id' => $admin->id,
                'nome_clinica' => $admin->nome_clinica,
                'slug_clinica' => $slugClinica
            ];

            $this->render('user/responder', $data, 'user/header', 'user/footer');
        } catch (\Exception $e) {
            $this->render('errors/500', [
                'message' => 'Erro interno do sistema.'
            ]);
        }
    }

    /**
     * Exibe página sobre a clínica (opcional)
     */
    public function about($slugClinica = null)
    {
        if ($slugClinica) {
            $admin = $this->findAdminByClinicSlug($slugClinica);

            $data = [
                'nome_clinica' => $admin ? $admin->nome_clinica : 'Clínica',
                'admin' => $admin
            ];
        } else {
            $data = [
                'nome_clinica' => 'Sistema de Anamnese'
            ];
        }

        $this->render('user/about', $data, 'user/header', 'user/footer');
    }

    /**
     * Busca admin pelo slug da clínica
     */
    private function findAdminByClinicSlug(string $slugClinica): ?Admin
    {
        // Busca todos os admins ativos e compara o slug
        $admins = Admin::where('ativo', true)->get();

        foreach ($admins as $admin) {
            if (Helpers::slugify($admin->nome_clinica) === $slugClinica) {
                return $admin;
            }
        }

        return null;
    }

    /**
     * Busca perguntas da clínica (próprias + fixas)
     */
    private function getPerguntasClinica(int $adminId): \Illuminate\Support\Collection
    {
        return Pergunta::where(function ($query) use ($adminId) {
            $query->where('admin_id', $adminId)
                ->orWhere(function ($q) {
                    $q->whereNull('admin_id')->where('fixa', 1);
                });
        })
            ->orderByRaw("CASE WHEN tipo = 'assinatura' THEN 1 ELSE 0 END") // Assinaturas por último
            ->orderBy('id')
            ->get();
    }
}
