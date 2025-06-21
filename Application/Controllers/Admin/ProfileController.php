<?php

namespace Application\Controllers\Admin;

use App\Models\Admin;
use Application\Core\ValidationException;

class ProfileController extends AdminController
{
    /**
     * Exibe perfil do admin
     */
    public function view($username)
    {
        // Valida se o username corresponde ao admin logado
        if ($username !== $this->adminUsername) {
            $this->redirect('admin/' . $this->adminUsername . '/perfil');
        }

        $this->renderAdmin('admin/profile/view');
    }

    /**
     * Exibe formulário de edição
     */
    public function edit($username)
    {
        // Valida se o username corresponde ao admin logado
        if ($username !== $this->adminUsername) {
            $this->redirect('admin/' . $this->adminUsername . '/perfil');
        }

        $this->renderAdmin('admin/profile/edit');
    }

    /**
     * Atualiza perfil completo
     */
    public function update($username)
    {
        try {
            $this->requirePost();

            // Valida se é o próprio admin
            if ($username !== $this->adminUsername) {
                $this->jsonError('Acesso negado', 403);
            }

            // Valida dados
            $validated = $this->request->validate([
                'username' => 'required|alpha_numeric|max_len,50',
                'nome_completo' => 'required|max_len,100',
                'email' => 'required|valid_email|max_len,100',
                'telefone' => 'required|max_len,20',
                'nome_clinica' => 'max_len,100',
                'razao_social' => 'max_len,100'
            ], [
                'username' => 'trim|sanitize_string',
                'nome_completo' => 'trim|sanitize_string',
                'email' => 'trim|sanitize_email',
                'telefone' => 'trim|sanitize_string',
                'nome_clinica' => 'trim|sanitize_string',
                'razao_social' => 'trim|sanitize_string'
            ]);

            // Verifica se username não está em uso por outro admin
            if ($validated['username'] !== $this->admin->username) {
                $exists = Admin::where('username', $validated['username'])
                    ->where('id', '!=', $this->adminId)
                    ->exists();

                if ($exists) {
                    $this->jsonError('Este nome de usuário já está em uso', 422);
                }
            }

            // Verifica se email não está em uso por outro admin
            if ($validated['email'] !== $this->admin->email) {
                $exists = Admin::where('email', $validated['email'])
                    ->where('id', '!=', $this->adminId)
                    ->exists();

                if ($exists) {
                    $this->jsonError('Este e-mail já está em uso', 422);
                }
            }

            // Atualiza dados
            $this->admin->fill($validated);
            $this->admin->save();

            // Atualiza sessão se username mudou
            if ($validated['username'] !== $this->adminUsername) {
                $_SESSION['admin_username'] = $validated['username'];
            }

            // Atualiza nome da clínica na sessão
            if (isset($validated['nome_clinica'])) {
                $_SESSION['nome_clinica'] = $validated['nome_clinica'];
            }

            $this->jsonSuccess('Perfil atualizado com sucesso!', [
                'redirect' => BASE_URL . 'admin/' . $validated['username'] . '/perfil'
            ]);
        } catch (ValidationException $e) {
            $this->jsonError('Erro de validação', 422, $e->getErrors());
        } catch (\Exception $e) {
            $this->jsonError('Erro ao atualizar perfil: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza campo específico (AJAX)
     */
    public function updateField()
    {
        try {
            $this->requirePost();

            $campo = $this->request->get('campo');
            $valor = $this->request->get('valor');

            $camposPermitidos = ['username', 'email', 'telefone', 'nome_completo'];

            if (!$campo || !in_array($campo, $camposPermitidos)) {
                $this->jsonError('Campo inválido', 400);
            }

            // Validações específicas
            switch ($campo) {
                case 'username':
                    if (!preg_match('/^[a-zA-Z0-9_]{4,30}$/', $valor)) {
                        $this->jsonError('Username inválido', 422);
                    }

                    if (Admin::where('username', $valor)->where('id', '!=', $this->adminId)->exists()) {
                        $this->jsonError('Username já está em uso', 422);
                    }
                    break;

                case 'email':
                    if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                        $this->jsonError('E-mail inválido', 422);
                    }

                    if (Admin::where('email', $valor)->where('id', '!=', $this->adminId)->exists()) {
                        $this->jsonError('E-mail já está em uso', 422);
                    }
                    break;
            }

            // Atualiza campo
            $this->admin->$campo = $valor;
            $this->admin->save();

            // Atualiza sessão se necessário
            if ($campo === 'username') {
                $_SESSION['admin_username'] = $valor;
            }

            $this->jsonSuccess('Campo atualizado com sucesso');
        } catch (\Exception $e) {
            $this->jsonError('Erro ao atualizar campo: ' . $e->getMessage());
        }
    }
}
