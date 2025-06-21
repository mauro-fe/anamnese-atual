<?php
// views/admin/profile/view.php
$pageTitle = "Visualizar Perfil";
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
                        <span class="text-muted"><?= $admin_username ?></span>
                    </li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Perfil</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Meu Perfil</h6>
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
                <!-- Card do Perfil -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-gradient-primary text-white text-center py-4">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0 text-white">
                                    <i class="fas fa-user-circle me-2"></i>Meu Perfil
                                </h3>
                                <small class="text-white-50">Informações da sua conta</small>
                            </div>
                            <div class="col-auto">
                                <a href="<?= BASE_URL ?>admin/<?= $admin_username ?>/perfil/editar"
                                    class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Informações Pessoais -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Informações Pessoais
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Nome de Usuário</label>
                                    <p class="fw-bold"><?= htmlspecialchars($admin->username ?? 'Não informado') ?></p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Nome Completo</label>
                                    <p class="fw-bold"><?= htmlspecialchars($admin->nome_completo ?? 'Não informado') ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">E-mail</label>
                                    <p class="fw-bold">
                                        <i class="fas fa-envelope me-1 text-info"></i>
                                        <?= htmlspecialchars($admin->email ?? 'Não informado') ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Telefone</label>
                                    <p class="fw-bold">
                                        <i class="fas fa-phone me-1 text-success"></i>
                                        <?= htmlspecialchars($admin->telefone ?? 'Não informado') ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Informações da Clínica -->
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-clinic-medical me-2"></i>Informações da Clínica
                                </h5>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Nome da Clínica</label>
                                    <p class="fw-bold"><?= htmlspecialchars($admin->nome_clinica ?? 'Não informado') ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Razão Social</label>
                                    <p class="fw-bold"><?= htmlspecialchars($admin->razao_social ?? 'Não informado') ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">CNPJ</label>
                                    <p class="fw-bold">
                                        <i class="fas fa-id-card me-1 text-warning"></i>
                                        <?= htmlspecialchars($admin->cnpj ?? 'Não informado') ?>
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Status da Conta</label>
                                    <p>
                                        <?php if (isset($admin->ativo) && $admin->ativo): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Ativa
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Inativa
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Informações da Conta -->
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informações da Conta
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Membro desde</label>
                                    <p class="fw-bold">
                                        <i class="fas fa-calendar me-1 text-info"></i>
                                        <?php if (isset($admin->created_at)): ?>
                                            <?= date('d/m/Y H:i', strtotime($admin->created_at)) ?>
                                        <?php else: ?>
                                            Não informado
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Último Login</label>
                                    <p class="fw-bold">
                                        <i class="fas fa-clock me-1 text-warning"></i>
                                        <?php if (isset($admin->ultimo_login) && $admin->ultimo_login): ?>
                                            <?= date('d/m/Y H:i', strtotime($admin->ultimo_login)) ?>
                                        <?php else: ?>
                                            Primeiro acesso
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="<?= BASE_URL ?>admin/<?= $admin_username ?>/home"
                                class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Voltar ao Dashboard
                            </a>

                            <div>
                                <a href="<?= BASE_URL ?>admin/<?= $admin_username ?>/perfil/editar"
                                    class="btn btn-primary me-2">
                                    <i class="fas fa-edit me-1"></i>Editar Perfil
                                </a>
                                <a href="<?= BASE_URL ?>admin/<?= $admin_username ?>/editCredentials"
                                    class="btn btn-outline-warning">
                                    <i class="fas fa-key me-1"></i>Alterar Senha
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Estatísticas (Opcional) -->
                <div class="card shadow">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2 text-success"></i>Estatísticas Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="fas fa-file-medical fa-2x text-primary mb-2"></i>
                                    <h4 class="fw-bold text-primary"><?= $totalFichas ?? 0 ?></h4>
                                    <p class="text-muted mb-0">Fichas Cadastradas</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="fas fa-question-circle fa-2x text-info mb-2"></i>
                                    <h4 class="fw-bold text-info"><?= $totalPerguntas ?? 0 ?></h4>
                                    <p class="text-muted mb-0">Perguntas Criadas</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <i class="fas fa-calendar fa-2x text-success mb-2"></i>
                                    <h4 class="fw-bold text-success">
                                        <?php
                                        if (isset($admin->created_at)) {
                                            $diff = date_diff(date_create($admin->created_at), date_create());
                                            echo $diff->days;
                                        } else {
                                            echo '0';
                                        }
                                        ?>
                                    </h4>
                                    <p class="text-muted mb-0">Dias de Uso</p>
                                </div>
                            </div>
                        </div>
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

<style>
    .card {
        border: none;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .bg-gradient-primary {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .fw-bold {
        font-size: 1.1rem;
        color: #344767;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.5em 0.75em;
    }

    hr {
        border-top: 2px solid #f8f9fa;
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

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .d-flex.justify-content-between .btn {
            width: 100%;
        }
    }
</style>