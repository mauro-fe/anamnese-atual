<!-- Adicione estes estilos ao seu CSS existente -->
<style>
    /* Estilização da Tabela - Versão Melhorada */
    #clientesAtivos,
    #clientesInativos {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    /* Cabeçalho da tabela - design mais moderno */
    #clientesAtivos thead,
    #clientesInativos thead {
        background: rgb(23, 86, 77);
    }

    #clientesAtivos th,
    #clientesInativos th {
        color: white;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 15px 12px;
        border: none;
    }

    /* Células da tabela com melhor espaçamento */
    #clientesAtivos td,
    #clientesInativos td {
        border-bottom: 1px solid #edf2f7;
        padding: 14px 12px;
        vertical-align: middle;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    /* Efeito hover mais suave */
    #clientesAtivos tbody tr:hover,
    #clientesInativos tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    /* Estilização de linhas alternadas */
    #clientesAtivos tbody tr:nth-child(even),
    #clientesInativos tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    /* Botões de ação mais modernos */
    .btn-dark {
        background: #3a3f51;
        border-radius: 6px;
        transition: all 0.3s ease;
        border: none;
        padding: 8px 16px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-dark:hover {
        background: #2c2f3a;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Cartões com sombras mais elegantes */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* Cabeçalho de cartão personalizado */
    .card-header {
        background: white;
        border-bottom: 1px solid #edf2f7;
        padding: 20px 20px;
    }

    .card-header h6 {
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        color: #3a3f51;
    }

    /* Paginação mais moderna */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #fff;
        border: none;
        border-radius: 6px;
        margin: 0 2px;
        padding: 6px 12px;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(45deg, #4e73df, #36b9cc);
        color: white !important;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f8fafc;
        color: #4e73df !important;
    }

    /* Badges mais modernos */
    .badge {
        padding: 6px 10px;
        font-weight: 600;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border-radius: 30px;
    }

    .bg-gradient-info {
        background: linear-gradient(45deg, #36b9cc, #1cc7d0);
    }

    .bg-gradient-danger {
        background: linear-gradient(45deg, #e74a3b, #ff7875);
    }

    /* Melhorar o layout dos filtros e busca */
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        margin-right: 10px;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        border-radius: 20px;
        padding-left: 30px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-search' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 10px center;
        background-size: 16px;
        border: 1px solid #e2e8f0;
    }

    /* Melhorar estilo de tabela vazia */
    .dataTables_empty {
        padding: 30px !important;
        font-size: 1rem;
        color: #718096;
        text-align: center;
        background: #f8fafc !important;
    }

    /* Botões de exportação mais modernos */
    .dt-buttons .btn {
        border-radius: 6px;
        padding: 8px 12px;
        margin-right: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .dt-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Melhoria de detalhes de clientes */
    .table .text-xs {
        font-size: 0.75rem;
        color: #718096;
    }

    .table .mb-0.text-sm {
        font-weight: 600;
        color: #3a3f51;
    }

    /* Melhorias para responsividade */
    @media screen and (max-width: 767px) {
        .card-header {
            padding: 15px;
        }

        .table-responsive {
            padding: 0 !important;
        }

        .dt-buttons {
            width: 100%;
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .dt-buttons .btn {
            margin-bottom: 5px;
        }

        div.dataTables_wrapper div.dataTables_filter {
            text-align: center;
            margin-right: 0;
        }
    }
</style>

<main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-sm border-radius-xl bg-white" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><span
                            class="text-muted"><?= $_SESSION['admin_username'] ?? 'Admin' ?></span>
                    </li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Painel Principal</h6>
            </nav>

            <ul class="navbar-nav justify-content-end">
                <!-- Formulário de busca CORRIGIDO -->
                <li class="collapse navbar-collapse mt-sm-0 mt-2" id="navbar">
                    <div class="d-flex align-items-center">
                        <form id="form-busca" class="d-flex align-items-center">
                            <div class="input-group flex-grow-1 btn-responsive">
                                <span class="input-group-text text-body">
                                    <i class="fas fa-search" aria-hidden="true"></i>
                                </span>
                                <input type="text" name="q" class="form-control" placeholder="Pesquisar fichas..."
                                    autocomplete="off" minlength="3">
                            </div>
                            <div>
                                <!-- Botão maior (visível em telas médias e maiores) -->
                                <button type="submit"
                                    class="btn btn-outline-primary btn-sm mt-3 ms-2 d-none d-md-inline">Pesquisar</button>

                                <!-- Botão menor (visível apenas em telas menores) -->
                                <button type="submit"
                                    class="btn btn-outline-primary btn-sm mt-3 ms-2 d-inline d-md-none btn-responsive">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </li>

                <li class="nav-item d-flex align-items-center">
                    <a href="<?= BASE_URL ?>admin/logout" class="nav-link text-body font-weight-bold px-0">
                        <button class="d-sm-inline d-none btn btn-primary mt-3 ms-3"><i
                                class="fas fa-sign-out-alt me-1"></i>Sair</button>
                    </a>
                </li>
                <li class="nav-item d-xl-none d-flex align-items-center ">
                    <a href="javascript:;" class="nav-link text-body pl-3" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Exibir mensagens -->
    <?php if (isset($error) && !empty($error)): ?>
        <div class="container-fluid">
            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($success) && !empty($success)): ?>
        <div class="container-fluid">
            <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tabela de Clientes Ativos -->
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-file-medical me-2"></i>
                            Fichas de Pacientes (<?= count($fichas ?? []) ?>)
                        </h6>
                        <a href="<?= BASE_URL ?>admin/fichas" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list me-1"></i>Ver todas
                        </a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <?php if (!empty($fichas) && count($fichas) > 0): ?>
                                <table id="clientesAtivos" class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 60%;">Informações do Paciente</th>
                                            <th style="width: 20%;">Data</th>
                                            <th style="width: 20%;">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fichas as $ficha): ?>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-2 text-sm">
                                                        <i class="fas fa-file-alt me-1 text-primary"></i>
                                                        Ficha #<?= htmlspecialchars($ficha['id']) ?>
                                                    </h6>

                                                    <?php if (!empty($ficha['nome_completo'])): ?>
                                                        <p class="text-sm fw-bold mb-1">
                                                            <i class="fas fa-user me-1 text-info"></i>
                                                            <?= htmlspecialchars($ficha['nome_completo']) ?>
                                                        </p>
                                                    <?php endif; ?>

                                                    <div class="row">
                                                        <?php if (!empty($ficha['telefone'])): ?>
                                                            <div class="col-md-6">
                                                                <p class="text-xs mb-1">
                                                                    <i class="fas fa-phone me-1"></i>
                                                                    <strong>Tel:</strong>
                                                                    <?= htmlspecialchars($ficha['telefone']) ?>
                                                                </p>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($ficha['sexo'])): ?>
                                                            <div class="col-md-6">
                                                                <p class="text-xs mb-1">
                                                                    <i class="fas fa-venus-mars me-1"></i>
                                                                    <strong>Sexo:</strong> <?= htmlspecialchars($ficha['sexo']) ?>
                                                                </p>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($ficha['idade'])): ?>
                                                            <div class="col-md-6">
                                                                <p class="text-xs mb-1">
                                                                    <i class="fas fa-birthday-cake me-1"></i>
                                                                    <strong>Idade:</strong> <?= htmlspecialchars($ficha['idade']) ?>
                                                                </p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <?php if (!empty($ficha['data_criacao'])): ?>
                                                        <p class="text-xs mb-0">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            <?= date('d/m/Y', strtotime($ficha['data_criacao'])) ?>
                                                        </p>
                                                        <p class="text-xs text-muted mb-0">
                                                            <?= date('H:i', strtotime($ficha['data_criacao'])) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="<?= BASE_URL ?>admin/ficha/<?= $ficha['slug_clinica'] ?>/<?= $ficha['slug_cliente'] ?>/<?= $ficha['id'] ?>"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> Visualizar
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                    <h5 class="text-muted">Nenhuma ficha encontrada</h5>
                                    <p class="text-muted mb-0">
                                        Quando os pacientes preencherem a ficha, elas aparecerão aqui.
                                    </p>
                                    <a href="<?= BASE_URL ?>admin/perguntas" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus me-1"></i>Criar perguntas
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
    $(document).ready(function() {
        // Configuração do DataTable
        const configPadrao = {
            pageLength: 10,
            responsive: true,
            dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>' +
                '<"row"<"col-12"tr>>' +
                '<"row mt-3"<"col-md-5"i><"col-md-7"p>>',

            buttons: [{
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-sm btn-success me-2'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-sm btn-danger me-2'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Imprimir',
                    className: 'btn btn-sm btn-primary me-2'
                }
            ],

            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "Nenhum registro encontrado",
                info: "Página _PAGE_ de _PAGES_ (_TOTAL_ registros)",
                infoEmpty: "Nenhum registro disponível",
                search: "Pesquisar:",
                searchPlaceholder: "Digite para buscar...",
                paginate: {
                    first: "Primeiro",
                    last: "Último",
                    next: "Próximo",
                    previous: "Anterior"
                }
            },

            stateSave: true,
            processing: true,
            searchDelay: 350,
            orderCellsTop: true,
            fixedHeader: true,

            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ]
        };

        // Inicializa DataTable se há dados
        if ($('#clientesAtivos tbody tr').length > 0) {
            $('#clientesAtivos').DataTable({
                ...configPadrao,
                order: [
                    [1, 'desc']
                ], // Ordena por data (mais recente primeiro)
            });
        }

        // Formulário de busca com AJAX
        $('#form-busca').on('submit', function(e) {
            e.preventDefault();

            const query = $('input[name="q"]').val().trim();

            if (query.length < 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Busca muito curta',
                    text: 'Digite pelo menos 3 caracteres para buscar.'
                });
                return;
            }

            // Mostra loading
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            // Faz busca via AJAX
            $.ajax({
                url: '<?= BASE_URL ?>admin/search',
                method: 'GET',
                data: {
                    q: query
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.data.results && response.data.results.length > 0) {
                            // Exibe resultados
                            let html = '<h5>Resultados da busca: "' + query + '"</h5>';
                            html += '<div class="list-group">';

                            response.data.results.forEach(function(result) {
                                html += '<div class="list-group-item">';
                                html += '<h6>Ficha #' + result.ficha_id + '</h6>';
                                result.matches.forEach(function(match) {
                                    html += '<p><strong>' + match.pergunta +
                                        ':</strong> ' + match.resposta + '</p>';
                                });
                                html += '</div>';
                            });

                            html += '</div>';

                            Swal.fire({
                                title: 'Resultados encontrados',
                                html: html,
                                showConfirmButton: true,
                                width: '80%'
                            });
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Nenhum resultado',
                                text: 'Não foram encontradas fichas com o termo "' +
                                    query + '".'
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro na busca',
                            text: response.message || 'Erro ao realizar busca.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Erro de conexão ao realizar busca.'
                    });
                },
                complete: function() {
                    // Restaura botão
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>