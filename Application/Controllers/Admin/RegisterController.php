<?php

namespace Application\Controllers\Admin;

use Application\Controllers\BaseController;
use App\Models\Admin;
use App\Lib\Helpers;
use Application\Core\ValidationException;

class RegisterController extends BaseController
{
    /**
     * Exibe formulário de cadastro
     */
    public function showRegisterForm()
    {
        // Se já está logado, redireciona
        if ($this->isAuthenticated()) {
            $this->redirect('admin/' . $_SESSION['admin_username'] . '/home');
        }

        $data = [
            'error' => $this->getError(),
            'success' => $this->getSuccess(),
            'form_data' => $_SESSION['form_data'] ?? []
        ];

        // Limpa dados do formulário da sessão
        unset($_SESSION['form_data']);

        $this->render('admin/admins/register', $data, null, 'admin/footer');
    }

    /**
     * Processa cadastro
     */
    public function processRegister()
    {
        try {
            $this->requirePost();

            // Salva dados do formulário na sessão para caso de erro
            $_SESSION['form_data'] = $this->request->all();

            // Adiciona validador customizado de CNPJ
            $this->addCnpjValidator();

            // Valida dados
            $validated = $this->request->validate([
                'username' => 'required|alpha_numeric|max_len,50',
                'nome_completo' => 'required|max_len,100',
                'email' => 'required|valid_email|max_len,100',
                'telefone' => 'required|max_len,20',
                'password' => 'required|min_len,8',
                'confirm_password' => 'required|equalsfield,password',
                'nome_clinica' => 'required|max_len,100',
                'razao_social' => 'required|max_len,100',
                'cnpj' => 'required|cnpj'
            ], [
                'username' => 'trim|sanitize_string',
                'nome_completo' => 'trim|sanitize_string',
                'email' => 'trim|sanitize_email',
                'telefone' => 'trim|sanitize_string',
                'password' => 'trim',
                'nome_clinica' => 'trim|sanitize_string',
                'razao_social' => 'trim|sanitize_string',
                'cnpj' => 'trim|sanitize_string'
            ]);

            // Validações adicionais
            $this->validateUniqueFields($validated);

            // Cria admin
            $admin = $this->createAdmin($validated);

            // Limpa dados do formulário
            unset($_SESSION['form_data']);

            $this->setSuccess('Administrador cadastrado com sucesso!');
            $this->redirect('admin/login');
        } catch (ValidationException $e) {
            $this->setError($this->formatValidationErrors($e->getErrors()));
            $this->redirect('admins/showRegisterForm');
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            $this->redirect('admins/showRegisterForm');
        }
    }

    /**
     * Adiciona validador customizado de CNPJ
     */
    private function addCnpjValidator(): void
    {
        $gump = new \GUMP();

        $gump->add_validator("cnpj", function ($field, $input, $param = null) {
            $cnpj = preg_replace('/[^0-9]/', '', $input[$field]);

            if (strlen($cnpj) != 14) return false;

            // CNPJ inválidos conhecidos
            if (preg_match('/^(\d)\1+$/', $cnpj)) return false;

            // Validação do primeiro dígito
            $soma = 0;
            $peso = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            for ($i = 0; $i < 12; $i++) {
                $soma += $cnpj[$i] * $peso[$i];
            }

            $resto = $soma % 11;
            $dig1 = $resto < 2 ? 0 : 11 - $resto;

            // Validação do segundo dígito
            $soma = 0;
            $peso = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            for ($i = 0; $i < 13; $i++) {
                $soma += $cnpj[$i] * $peso[$i];
            }

            $resto = $soma % 11;
            $dig2 = $resto < 2 ? 0 : 11 - $resto;

            return ($cnpj[12] == $dig1 && $cnpj[13] == $dig2);
        }, 'CNPJ inválido');

        // Define o validador globalmente
        \GUMP::set_field_name("cnpj", "CNPJ");
    }

    /**
     * Valida campos únicos
     */
    private function validateUniqueFields(array $validated): void
    {
        // Valida username único
        if (Admin::where('username', $validated['username'])->exists()) {
            throw new \Exception('O nome de usuário já está em uso.');
        }

        // Valida email único
        if (Admin::where('email', $validated['email'])->exists()) {
            throw new \Exception('O e-mail já está cadastrado.');
        }

        // Valida CNPJ único
        if (Admin::where('cnpj', $validated['cnpj'])->exists()) {
            throw new \Exception('Este CNPJ já está cadastrado.');
        }

        // Valida username com Helper
        $username = Helpers::validateUsername($validated['username']);
        if (!$username) {
            throw new \Exception('Nome de usuário inválido.');
        }
    }

    /**
     * Cria novo admin
     */
    private function createAdmin(array $validated): Admin
    {
        return Admin::create([
            'username' => $validated['username'],
            'nome_completo' => $validated['nome_completo'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'password' => $validated['password'], // Será hasheado automaticamente pelo mutator
            'nome_clinica' => $validated['nome_clinica'],
            'razao_social' => $validated['razao_social'],
            'cnpj' => $validated['cnpj'],
            'ativo' => true,
            'password_changed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Formata erros de validação
     */
    private function formatValidationErrors(array $errors): string
    {
        if (is_array($errors) && count($errors) > 0) {
            return Helpers::formatErrorHtml($errors);
        }

        return 'Erro de validação nos dados informados.';
    }
}
