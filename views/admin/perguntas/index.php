<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #1cc88a;
        --accent-color: #f6c23e;
        --danger-color: #e74a3b;
    }

    body {
        background-color: #f8f9fc;
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }


    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.25);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.35rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-success {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .btn-warning {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(78, 115, 223, 0.05);
    }

    .form-pergunta-model .btn {
        padding: 2px 25px;
        margin-left: 5px;
        margin-bottom: 0;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .banner {
        background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .fade-in {
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-scale {
        transition: transform 0.3s ease;
    }

    .animate-scale:hover {
        transform: scale(1.02);
    }
</style>


<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-sm border-radius-xl bg-white" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-1 px-3 ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark"
                            href="javascript:;"><?= $_SESSION['admin_username'] ?></a>
                    </li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Perguntas</li>
                </ol>
                <a class="navbar-brand" href="#"><i class="fas fa-question-circle me-2"></i> Gestão de Perguntas</a>
            </nav>
            <div class="d-flex ms-auto mt-3">
                <ul class="navbar-nav d-flex justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                        <a href="<?= BASE_URL ?>admin/logout" class="nav-link text-body font-weight-bold px-0">
                            <button class="d-sm-inline d-none btn btn-primary mt-3 ms-3"><i
                                    class="fas fa-sign-out-alt me-1"></i>Sair</button>
                        </a>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="banner shadow">
                    <h2><i class="fas fa-list-alt me-2"></i> Gerenciamento de Perguntas</h2>
                    <p class="mb-0">Crie e gerencie perguntas para questionários e formulários para seu cliente.</p>
                </div>
            </div>
        </div>

        <div class="column">
            <!-- Formulário de criação -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow fade-in animate-scale">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i> Crie uma nova Pergunta</h4>
                    </div>
                    <div class="card-body">
                        <!-- Selecionar modelo de pergunta pronta -->
                        <form action="<?= BASE_URL ?>admin/perguntas/usar-modelo" method="POST"
                            class="mb-4 form-pergunta-model">
                            <div class="mb-3">
                                <label for="modelo_id" class="form-label fw-bold">
                                    Adicionar pergunta pronta:
                                </label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text"> <i class="fas fa-magic me-1"></i></span>
                                        <select class="form-select" name="modelo_id" id="modelo_id" required>
                                            <option value="">Escolha uma pergunta</option>
                                            <?php foreach ($perguntas_modelo as $modelo): ?>
                                                <option value="<?= (int)$modelo->id ?>">
                                                    <?= htmlspecialchars($modelo->pergunta) ?>
                                                    (<?= htmlspecialchars($modelo->tipo) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-plus-circle me-1"></i> Usar
                                    </button>
                                </div>
                            </div>

                        </form>


                        <form id="formPergunta" action="<?= BASE_URL ?>admin/perguntas/salvar" method="POST">
                            <div class="mb-3">
                                <label for="pergunta" class="form-label">Pergunta</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-question"></i></span>
                                    <input type="text" class="form-control" id="pergunta" name="pergunta" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="chave" class="form-label">Chave técnica (opcional)</label>
                                <input type="text" name="chave" id="chave" class="form-control"
                                    placeholder="Ex: nome, email, idade...">
                                <div class="form-text">Use somente letras minúsculas, sem espaços (ex: nome_completo)
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Resposta</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                    <select class="form-select" id="tipo" name="tipo">
                                        <option value="texto">Texto</option>
                                        <option value="checkbox">Caixa de seleção</option>
                                        <option value="radio">Escolha única</option>
                                        <option value="select">Lista suspensa</option>
                                        <option value="textarea">Texto longo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3" id="opcoes-container" style="display: none;">
                                <label for="opcoes" class="form-label">Opções (separadas por vírgula)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                    <input type="text" class="form-control" id="opcoes" name="opcoes"
                                        placeholder="Ex: Sim,Não,Às vezes">
                                </div>
                                <div class="form-text text-muted">Digite as opções separadas por vírgula</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="obrigatorio"
                                            id="obrigatorio">
                                        <label class="form-check-label" for="obrigatorio">
                                            <i class="fas fa-asterisk text-danger me-1 small"></i> Resposta
                                            obrigatória
                                        </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fixa" id="fixa">
                                        <label class="form-check-label" for="fixa">
                                            <i class="fas fa-thumbtack me-1"></i> Pergunta fixa
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="complemento_texto"
                                        id="complemento_texto">
                                    <label class="form-check-label" for="complemento_texto">
                                        <i class="fas fa-pen me-1"></i> Permitir resposta adicional em texto
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3" id="placeholder-container" style="display: none;">
                                <label for="placeholder" class="form-label">Texto de ajuda (placeholder)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                    <input type="text" class="form-control" id="placeholder" name="placeholder"
                                        placeholder="Ex: Explique aqui...">
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success"><i
                                        class="fas fa-save me-2"></i>Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabela de perguntas -->
            <div class="col-lg-12">
                <div class="card shadow fade-in animate-scale">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i> Perguntas Cadastradas
                        </h4>
                        <span class="badge bg-primary rounded-pill" id="contador-perguntas">
                            <?= count($perguntas) ?> pergunta(s)
                        </span>
                    </div>
                    <div class="card-body">
                        <?php if (count($perguntas) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle text-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Pergunta</th>
                                            <th>Tipo</th>
                                            <th class="text-center">Obrigatória</th>
                                            <th class="text-center">Fixa</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($perguntas as $p): ?>
                                            <tr>
                                                <td>
                                                    <?= htmlspecialchars($p->pergunta) ?>
                                                    <?php if (is_null($p->admin_id)): ?>
                                                        <span class="badge bg-dark ms-1" title="Pergunta global (modelo)">
                                                            <i class="fas fa-globe"></i> Global
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $tipoIcones = [
                                                        'texto' => 'fa-font',
                                                        'checkbox' => 'fa-check-square',
                                                        'radio' => 'fa-dot-circle',
                                                        'select' => 'fa-caret-square-down',
                                                        'textarea' => 'fa-align-left',
                                                        'date' => 'fa-calendar',
                                                        'tel' => 'fa-phone',
                                                        'number' => 'fa-sort-numeric-up',
                                                        'assinatura' => 'fa-pen-fancy'
                                                    ];
                                                    $icon = $tipoIcones[$p->tipo] ?? 'fa-question-circle';
                                                    ?>
                                                    <i class="fas <?= $icon ?> me-1"></i> <?= ucfirst($p->tipo) ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $p->obrigatorio
                                                        ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Sim</span>'
                                                        : '<span class="badge bg-secondary"><i class="fas fa-times me-1"></i>Não</span>' ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= $p->fixa
                                                        ? '<span class="badge bg-primary"><i class="fas fa-thumbtack me-1"></i>Sim</span>'
                                                        : '<span class="badge bg-secondary"><i class="fas fa-times me-1"></i>Não</span>' ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!$p->fixa): ?>
                                                        <button class="btn btn-sm btn-outline-danger excluir-pergunta"
                                                            data-id="<?= $p->id ?>"
                                                            data-pergunta="<?= htmlspecialchars($p->pergunta) ?>">
                                                            <i class="fas fa-trash-alt me-1"></i> Excluir
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted" title="Pergunta fixa, não pode ser removida">
                                                            <i class="fas fa-lock"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i> Nenhuma pergunta cadastrada ainda.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.12/sweetalert2.min.js"></script>

<!-- JS para lógica dinâmica -->
<script>
    // Elementos DOM
    const tipo = document.getElementById('tipo');
    const opcoesContainer = document.getElementById('opcoes-container');
    const complementoCheckbox = document.getElementById('complemento_texto');
    const placeholderContainer = document.getElementById('placeholder-container');
    const formPergunta = document.getElementById('formPergunta');
    const excluirBotoes = document.querySelectorAll('.excluir-pergunta');

    // Função para verificar o tipo e mostrar/esconder o container de opções
    function verificarTipo() {
        const tiposComOpcoes = ['checkbox', 'radio', 'select'];

        if (tiposComOpcoes.includes(tipo.value)) {
            opcoesContainer.style.display = 'block';
            opcoesContainer.classList.add('fade-in');
        } else {
            opcoesContainer.style.display = 'none';
            opcoesContainer.classList.remove('fade-in');
        }
    }

    // Função para verificar se o complemento está marcado
    function verificarComplemento() {
        if (complementoCheckbox.checked) {
            placeholderContainer.style.display = 'block';
            placeholderContainer.classList.add('fade-in');
        } else {
            placeholderContainer.style.display = 'none';
            placeholderContainer.classList.remove('fade-in');
        }
    }

    // Event listeners
    tipo.addEventListener('change', verificarTipo);
    complementoCheckbox.addEventListener('change', verificarComplemento);

    // Submit do formulário com validação e SweetAlert
    formPergunta.addEventListener('submit', function(e) {
        e.preventDefault();

        const perguntaValor = document.getElementById('pergunta').value.trim();
        const tipoValor = tipo.value;
        const opcoesValor = document.getElementById('opcoes').value.trim();

        // Validação das opções para tipos que necessitam
        if (['checkbox', 'radio', 'select'].includes(tipoValor) && opcoesValor === '') {
            Swal.fire({
                icon: 'error',
                title: 'Ops!',
                text: 'Por favor, informe pelo menos uma opção para este tipo de pergunta.',
                confirmButtonColor: '#4e73df'
            });
            return;
        }

        // Confirmar criação
        Swal.fire({
            title: 'Confirmar criação',
            text: `Deseja salvar a pergunta "${perguntaValor}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#e74a3b',
            confirmButtonText: 'Sim, salvar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                formPergunta.submit();
            }
        });
    });

    // Exclusão com SweetAlert
    excluirBotoes.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const pergunta = this.getAttribute('data-pergunta');

            Swal.fire({
                title: 'Confirmar exclusão',
                html: `Você tem certeza que deseja excluir a pergunta:<br><strong>${pergunta}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?= BASE_URL ?>admin/perguntas/excluir/${id}`;
                }
            });
        });
    });

    // Inicialização na carga da página
    window.addEventListener('DOMContentLoaded', () => {
        verificarTipo();
        verificarComplemento();

        // Toast de boas-vindas
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: 'info',
            title: 'Gerenciador de perguntas carregado'
        });
    });

    // Verificar se há mensagem de confirmação para exibir (vinda do PHP)
    <?php if (isset($_SESSION['mensagem'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['tipo'] ?? 'success' ?>',
            title: '<?= $_SESSION['titulo'] ?? 'Sucesso!' ?>',
            text: '<?= $_SESSION['mensagem'] ?>',
            confirmButtonColor: '#4e73df'
        });
        <?php unset($_SESSION['mensagem'], $_SESSION['tipo'], $_SESSION['titulo']); ?>
    <?php endif; ?>
</script>