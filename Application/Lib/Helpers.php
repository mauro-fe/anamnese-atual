<?php

namespace App\Lib;

use App\Models\Ficha;

class Helpers
{
    /**
     * Valida e sanitiza o nome de usuário.
     */
    public static function validateUsername($username)
    {
        $username = trim(strip_tags($username));

        if (!preg_match('/^[a-zA-Z0-9_]{4,30}$/', $username)) {
            return false;
        }

        return $username;
    }

    /**
     * Gera um slug robusto, com suporte a acentuação.
     */
    public static function slugify($text)
    {
        if (empty($text)) {
            return 'slug';
        }

        // Remove acentos
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        // Substitui caracteres especiais por hífen
        $text = preg_replace('/[^a-zA-Z0-9]+/', '-', $text);

        // Remove hífens do início e fim
        $text = trim($text, '-');

        // Converte para minúsculas
        $text = strtolower($text);

        return $text ?: 'slug';
    }

    /**
     * Verifica se a ficha pertence ao administrador.
     */
    public static function isOwnerOfFicha($fichaId, $adminId)
    {
        return Ficha::where('id', $fichaId)
            ->where('admin_id', $adminId)
            ->exists();
    }

    /**
     * Formata mensagens de erro para exibição em HTML.
     */
    public static function formatErrorHtml($message)
    {
        if (is_array($message)) {
            $html = '';
            foreach ($message as $erro) {
                $html .= '<div class="alert alert-danger mb-2">' . htmlspecialchars($erro) . '</div>';
            }
            return $html;
        }

        return '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
    }

    /**
     * Sanitiza entrada de dados
     */
    public static function sanitize($value)
    {
        if (is_array($value)) {
            return array_map([self::class, 'sanitize'], $value);
        }

        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Formata telefone para exibição
     */
    public static function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 11) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
        } elseif (strlen($phone) === 10) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
        }

        return $phone;
    }

    /**
     * Gera hash seguro para tokens
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Valida CNPJ
     */
    public static function validateCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14) return false;

        // CNPJ inválidos conhecidos
        if (preg_match('/^(\d)\1+$/', $cnpj)) return false;

        // Validação dos dígitos verificadores
        $soma = 0;
        $peso = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $peso[$i];
        }

        $resto = $soma % 11;
        $dig1 = $resto < 2 ? 0 : 11 - $resto;

        $soma = 0;
        $peso = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $peso[$i];
        }

        $resto = $soma % 11;
        $dig2 = $resto < 2 ? 0 : 11 - $resto;

        return ($cnpj[12] == $dig1 && $cnpj[13] == $dig2);
    }
}
