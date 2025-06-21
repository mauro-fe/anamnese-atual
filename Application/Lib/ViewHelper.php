<?php

namespace App\Lib;

use App\Lib\Helpers;
use App\Models\Ficha;

class ViewHelper
{
    public static function getSharedData()
    {
        // Dados da sessão
        $admin_id = $_SESSION['admin_id'] ?? null;
        $admin_username = $_SESSION['admin_username'] ?? null;
        $nome_clinica = $_SESSION['nome_clinica'] ?? 'Clínica';

        // Gera slug da clínica
        $slug_clinica = Helpers::slugify($nome_clinica);

        // Busca primeira ficha do admin (se houver)
        $ficha_id = null;
        if ($admin_id) {
            $ficha_id = Ficha::where('admin_id', $admin_id)
                ->orderBy('created_at', 'desc')
                ->value('id');
        }

        return [
            'admin_id' => $admin_id,
            'admin_username' => $admin_username,
            'nome_clinica' => $nome_clinica,
            'slug_clinica' => $slug_clinica,
            'ficha_id' => $ficha_id ?? 0,
            'base_url' => BASE_URL,
            'current_year' => date('Y')
        ];
    }

    /**
     * Gera URL para admin
     */
    public static function adminUrl($path = '', $username = null)
    {
        $username = $username ?? ($_SESSION['admin_username'] ?? 'admin');
        return BASE_URL . 'admin/' . $username . '/' . ltrim($path, '/');
    }

    /**
     * Gera URL pública
     */
    public static function publicUrl($path = '')
    {
        return BASE_URL . ltrim($path, '/');
    }
}
