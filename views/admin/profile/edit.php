<?php
// views/admin/profile/edit.php
$pageTitle = "Editar Perfil";
$admin_username = $_SESSION['admin_username'] ?? 'admin';
?>

<main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-sm border-radius-xl bg-white" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a href="<?= BASE_URL ?>admin/<?= $admin_username ?>/perfil"
                            class="text-muted"><?= $admin_username ?></a>
                    </li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Editar Perfil</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Editar Perfil</h6>
            </nav>

            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a href="<?= BASE_URL ?>admin/logout" class="nav-link text-body font-weight-bold px-0">
                        <button class="d-sm-inline d-none btn btn-primary mt-3 ms-3">
                            <i class="fas fa-sign-out-alt me-1"></i>Sair
                        </button>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-gradient-primary text-white text-center py-4">
                        <h3 class="mb-0 text-white">
                            <i class="fas fa-edit me-2"></i>Editar Perfil
                        </h3>
                        <small class="text-white-50">Atualize suas informações pessoais</small>
                    </div>

                    <div class="card-body p-4">
                        <!-- CORREÇÃO: URL atualizada para o novo controller -->
                        <form id="form-profile" action="<?= BASE_URL ?>admin/<?= $admin_username ?>/perfil/update"
                            method="POST" autocomplete="off" novalidate>

                            <div class="row">
                                <!-- Coluna Esquerda - Dados Pessoais -->
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Dados Pessoais
                                    </h5>

                                    <!-- Username -->
                                    <div class="form-group mb-3">
                                        <label for="username" class="form-label">
                                            Nome de Usuário <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="<?= htmlspecialchars($admin->username ?? '') ?>" required
                                                minlength="3" maxlength="30" pattern="^[a-zA-Z0-9_]{3,30}$">
                                            <div class="invalid-feedback">
                                                O nome de usuário deve ter entre 3 e 30 caracteres (apenas letras,
                                                números e _).
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nome Completo -->
                                    <div class="form-group mb-3">
                                        <label for="nome_completo" class="form-label">
                                            Nome Completo <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control" id="nome_completo"
                                                name="nome_completo"
                                                value="<?= htmlspecialchars($admin->nome_completo ?? '') ?>" required
                                                maxlength="100">
                                            <div class="invalid-feedback">
                                                Por favor, informe seu nome completo.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">
                                            E-mail <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?= htmlspecialchars($admin->email ?? '') ?>" required
                                                maxlength="100">
                                            <div class="invalid-feedback">
                                                Por favor, insira um endereço de e-mail válido.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Telefone -->
                                    <div class="form-group mb-3">
                                        <label for="telefone" class="form-label">Telefone</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control" id="telefone" name="telefone"
                                                value="<?= htmlspecialchars($admin->telefone ?? '') ?>" maxlength="20"
                                                placeholder="(00) 00000-0000">
                                        </div>
                                    </div>
                                </div>

                                <!-- Coluna Direita - Dados da Clínica -->
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-clinic-medical me-2"></i>Dados da Clínica
                                    </h5>

                                    <!-- Nome da Clínica -->
                                    <div class="form-group mb-3">
                                        <label for="nome_clinica" class="form-label">Nome da Clínica</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hospital"></i></span>
                                            <input type="text" class="form-control" id="nome_clinica"
                                                name="nome_clinica"
                                                value="<?= htmlspecialchars($admin->nome_clinica ?? '') ?>"
                                                maxlength="100">
                                        </div>
                                    </div>

                                    <!-- Razão Social -->
                                    <div class="form-group mb-3">
                                        <label for="razao_social" class="form-label">Razão Social</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            <input type="text" class="form-control" id="razao_social"
                                                name="razao_social"
                                                value="<?= htmlspecialchars($admin->razao_social ?? '') ?>"
                                                maxlength="100">
                                        </div>
                                    </div>

                                    <!-- CNPJ -->
                                    <div class="form-group mb-3">
                                        <label for="cnpj" class="form-label">CNPJ</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control" id="cnpj" name="cnpj"
                                                value="<?= htmlspecialchars($admin->cnpj ?? '') ?>" maxlength="18"
                                                placeholder="00.000.000/0001-00">
                                        </div>
                                    </div>

                                    <!-- Informações Adicionais -->
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <small>
                                            <strong>Dica:</strong> Mantenha seus dados sempre atualizados para
                                            garantir o melhor funcionamento do sistema.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="<?= BASE_URL ?>admin/<?= $admin_username ?>/perfil"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary" id="btn-save">
                                    <i class="fas fa-save me-1"></i>Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Exibir mensagens se houver -->
<?php if (isset($error) && !empty($error)): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'error',
        title: 'Erro!',
        text: '<?= htmlspecialchars($error) ?>',
        confirmButtonColor: '#e74a3b'
    });
});
</script>
<?php endif; ?>

<?php if (isset($success) && !empty($success)): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '<?= htmlspecialchars($success) ?>',
        confirmButtonColor: '#28a745'
    });
});
</script>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Aplicar máscara para telefone
    const telefoneInput = document.getElementById("telefone");
    if (telefoneInput) {
        telefoneInput.addEventListener("input", function(e) {
            let value = e.target.value.replace(/\D/g, "");
            if (value.length > 11) value = value.slice(0, 11);

            if (value.length > 2) {
                value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            }
            if (value.length > 10) {
                value = value.substring(0, 10) + '-' + value.substring(10);
            }

            e.target.value = value;
        });
    }

    // Aplicar máscara para CNPJ
    const cnpjInput = document.getElementById("cnpj");
    if (cnpjInput) {
        cnpjInput.addEventListener("input", function(e) {
            let value = e.target.value.replace(/\D/g, "");
            if (value.length > 14) value = value.slice(0, 14);

            value = value.replace(/^(\d{2})(\d)/, "$1.$2");
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            value = value.replace(/\.(\d{3})(\d)/, ".$1/$2");
            value = value.replace(/(\d{4})(\d)/, "$1-$2");

            e.target.value = value;
        });
    }

    // Validação do formulário
    const formProfile = document.getElementById("form-profile");
    if (formProfile) {
        formProfile.addEventListener("submit", function(e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add("was-validated");

                Swal.fire({
                    icon: 'warning',
                    title: 'Dados inválidos',
                    text: 'Por favor, corrija os campos destacados em vermelho.',
                    confirmButtonColor: '#f39c12'
                });
                return;
            }

            // Confirmar alterações
            Swal.fire({
                title: 'Confirmar alterações',
                text: 'Deseja salvar as alterações no seu perfil?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, salvar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar indicador de carregamento
                    const btnSave = document.getElementById("btn-save");
                    const originalText = btnSave.innerHTML;
                    btnSave.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Salvando...';
                    btnSave.disabled = true;

                    // Enviar formulário via AJAX
                    const formData = new FormData(formProfile);

                    fetch(formProfile.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(async response => {
                            const rawText = await response.text();
                            try {
                                return JSON.parse(rawText);
                            } catch (err) {
                                console.error("Erro ao interpretar JSON:", rawText);
                                throw new Error("Resposta inválida do servidor.");
                            }
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso!',
                                    text: data.message,
                                    confirmButtonColor: '#28a745'
                                }).then(() => {
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    } else {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: data.message ||
                                        'Erro ao salvar alterações.',
                                    confirmButtonColor: '#e74a3b'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro de conexão. Tente novamente.',
                                confirmButtonColor: '#e74a3b'
                            });
                        })
                        .finally(() => {
                            // Restaurar botão
                            btnSave.innerHTML = originalText;
                            btnSave.disabled = false;
                        });
                }
            });
        });
    }

    // Remover feedback de erro ao digitar
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
}

.card {
    border: none;
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.form-label {
    font-weight: 600;
    color: #344767;
    font-size: 0.875rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #495057;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.btn {
    border-radius: 0.5rem;
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.alert {
    border: none;
    border-radius: 0.75rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }

    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>