<?php
// views/admin/fichas/index.php
$admin_username = $_SESSION['admin_username'] ?? 'admin';
?>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-sm border-radius-xl bg-white" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a href="<?= BASE_URL ?>admin/<?= $admin_username ?>/home"
                            class="text-muted"><?= $admin_username ?></a>
                    </li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Fichas</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">
                    <i class="fas fa-file-medical me-2"></i>Gestão de Fichas
                </h6>
            </nav>

            <ul class="navbar-nav justify-content-end">
                <!-- Formulário de busca -->
                <li class="collapse navbar-collapse mt-sm-0 mt-2" id="navbar">
                    <div class="d-flex align-items-center">
                        <form id="form-busca-fichas" class="d-flex align-items-center">
                            <div class="input-group flex-grow-1 btn-responsive">
                                <span class="input-group-text text-body">
                                    <i class="fas fa-search" aria-hidden="true"></i>
                                </span>
                                <input type="text" name="q" class="form-control"
                                    placeholder="Buscar por nome, telefone..." autocomplete="off" minlength="3">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-outline-primary btn-sm mt-3 ms-2">
                                    <i class="fas fa-search me-1"></i>Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </li>

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

    <div class="container-fluid py-4">
        <!-- Estatísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total de Fichas</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?= count($fichas ?? []) ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-file-medical text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Fichas Hoje</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php
                                        $hoje = 0;
                                        if (isset($fichas) && is_array($fichas)) {
                                            foreach ($fichas as $ficha) {
                                                if (
                                                    isset($ficha->created_at) &&
                                                    date('Y-m-d', strtotime($ficha->created_at)) === date('Y-m-d')
                                                ) {
                                                    $hoje++;
                                                }
                                            }
                                        }
                                        echo $hoje;
                                        ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-calendar-check text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Esta Semana</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        <?php
                                        $semana = 0;
                                        if (isset($fichas) && is_array($fichas)) {
                                            $inicioSemana = date('Y-m-d', strtotime('monday this week'));
                                            foreach ($fichas as $ficha) {
                                                if (
                                                    isset($ficha->created_at) &&
                                                    date('Y-m-d', strtotime($ficha->created_at)) >= $inicioSemana
                                                ) {
                                                    $semana++;
                                                }
                                            }
                                        }
                                        echo $semana;
                                        ?>
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="fas fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Ações</p>
                                    <div class="d-flex">
                                        <a href="<?= BASE_URL ?>admin/fichas/lixeira"
                                            class="btn btn-sm btn-outline-warning me-2">
                                            <i class="fas fa-trash me-1"></i>Lixeira
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="fas fa-cog text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Fichas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Lista de Fichas (<?= count($fichas ?? []) ?>)
                        </h6>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" onclick="exportarExcel()">
                                <i class="fas fa-file-excel me-1"></i>Excel
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="exportarPDF()">
                                <i class="fas fa-file-pdf me-1"></i>PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <?php if (!empty($fichas) && count($fichas) > 0): ?>
                            <table id="tabelaFichas" class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Paciente
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Contato
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Data
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($fichas as $ficha): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">
                                                        <?php
                                                                $nomeCompleto = '';
                                                                if (isset($ficha->respostas)) {
                                                                    foreach ($ficha->respostas as $resposta) {
                                                                        if (isset($resposta->pergunta) && $resposta->pergunta->chave === 'nome_completo') {
                                                                            $nomeCompleto = $resposta->resposta;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                echo !empty($nomeCompleto) ? htmlspecialchars($nomeCompleto) : 'Nome não informado';
                                                                ?>
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        Ficha #<?= $ficha->id ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                                    $telefone = $email = '';
                                                    if (isset($ficha->respostas)) {
                                                        foreach ($ficha->respostas as $resposta) {
                                                            if (isset($resposta->pergunta)) {
                                                                if ($resposta->pergunta->chave === 'telefone') {
                                                                    $telefone = $resposta->resposta;
                                                                } elseif ($resposta->pergunta->chave === 'email') {
                                                                    $email = $resposta->resposta;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                            <p class="text-xs mb-0">
                                                <?php if ($telefone): ?>
                                                <i class="fas fa-phone me-1"></i><?= htmlspecialchars($telefone) ?><br>
                                                <?php endif; ?>
                                                <?php if ($email): ?>
                                                <i class="fas fa-envelope me-1"></i><?= htmlspecialchars($email) ?>
                                                <?php endif; ?>
                                                <?php if (!$telefone && !$email): ?>
                                                <span class="text-muted">Não informado</span>
                                                <?php endif; ?>
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <?php
                                                        if (isset($ficha->created_at)) {
                                                            echo is_string($ficha->created_at) ?
                                                                date('d/m/Y H:i', strtotime($ficha->created_at)) :
                                                                $ficha->created_at->format('d/m/Y H:i');
                                                        } else {
                                                            echo 'N/A';
                                                        }
                                                        ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <?php
                                                    $status = $ficha->status ?? 'ativa';
                                                    ?>
                                            <span
                                                class="badge badge-sm bg-gradient-<?= $status === 'ativa' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <?php
                                                        $slugClinica = \App\Lib\Helpers::slugify($_SESSION['nome_clinica'] ?? 'clinica');
                                                        $slugPaciente = !empty($nomeCompleto) ? \App\Lib\Helpers::slugify($nomeCompleto) : 'paciente';
                                                        ?>
                                                <a href="<?= BASE_URL ?>admin/ficha/<?= $slugClinica ?>/<?= $slugPaciente ?>/<?= $ficha->id ?>"
                                                    class="btn btn-sm btn-outline-primary" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>admin/ficha/exportar-pdf/<?= $ficha->id ?>"
                                                    class="btn btn-sm btn-outline-danger" title="Exportar PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-warning"
                                                    onclick="excluirFicha(<?= $ficha->id ?>)" title="Excluir">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- Paginação Manual (se necessário) -->
                            <?php if (isset($pagination)): ?>
                            <div class="d-flex justify-content-center mt-3">
                                <nav aria-label="Paginação">
                                    <ul class="pagination">
                                        <?php if ($pagination['current_page'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?page=<?= $pagination['current_page'] - 1 ?>">Anterior</a>
                                        </li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                        <?php endfor; ?>

                                        <?php if ($pagination['has_more_pages']): ?>
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?page=<?= $pagination['current_page'] + 1 ?>">Próximo</a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                            <?php endif; ?>

                            <!-- Informação sobre limitação -->
                            <?php if (count($fichas) >= 50): ?>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Mostrando os primeiros 50 registros. Use a busca para encontrar fichas específicas.
                            </div>
                            <?php endif; ?>
                            <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                                <h5 class="text-muted">Nenhuma ficha encontrada</h5>
                                <p class="text-muted mb-0">
                                    Quando os pacientes preencherem as fichas, elas aparecerão aqui.
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // Configurar DataTable se há dados (sem paginação do servidor)
    if ($('#tabelaFichas tbody tr').length > 0) {
        $('#tabelaFichas').DataTable({
            pageLength: 25,
            responsive: true,
            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "Nenhum registro encontrado",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "Nenhum registro disponível",
                search: "Pesquisar:",
                paginate: {
                    first: "Primeiro",
                    last: "Último",
                    next: "Próximo",
                    previous: "Anterior"
                }
            },
            order: [
                [2, 'desc']
            ], // Ordena por data
            columnDefs: [{
                targets: [4], // Coluna de ações
                orderable: false
            }]
        });
    }

    // Busca personalizada
    $('#form-busca-fichas').on('submit', function(e) {
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

        // Fazer busca via AJAX
        $.ajax({
            url: '<?= BASE_URL ?>admin/fichas/buscar',
            method: 'GET',
            data: {
                q: query
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Mostrar resultados em modal ou redirecionar
                    if (response.data.fichas && response.data.fichas.length > 0) {
                        Swal.fire({
                            title: 'Resultados da busca',
                            text: 'Encontradas ' + response.data.total +
                                ' ficha(s)',
                            icon: 'success'
                        });
                        // Aqui você pode atualizar a tabela com os resultados
                    } else {
                        Swal.fire({
                            title: 'Nenhum resultado',
                            text: 'Não foram encontradas fichas com este termo.',
                            icon: 'info'
                        });
                    }
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Erro',
                    text: 'Erro ao realizar busca.',
                    icon: 'error'
                });
            }
        });
    });
});

// Função para excluir ficha
function excluirFicha(fichaId) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Você tem certeza que deseja excluir esta ficha?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= BASE_URL ?>admin/ficha/excluir/' + fichaId, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Excluída!',
                            text: data.message,
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: data.message || 'Erro ao excluir ficha.',
                            confirmButtonColor: '#e74a3b'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro de conexão.',
                        confirmButtonColor: '#e74a3b'
                    });
                });
        }
    });
}

// Funções de exportação (placeholder)
function exportarExcel() {
    Swal.fire({
        icon: 'info',
        title: 'Em desenvolvimento',
        text: 'Funcionalidade de exportação será implementada em breve.'
    });
}

function exportarPDF() {
    Swal.fire({
        icon: 'info',
        title: 'Em desenvolvimento',
        text: 'Funcionalidade de exportação será implementada em breve.'
    });
}
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #56ab2f 0%, #a8e6cf 100%);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #3498db 0%, #85c1e9 100%);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #f39c12 0%, #f7dc6f 100%);
}

.icon-shape {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin: 0 2px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
        gap: 4px;
    }

    .card-body {
        padding: 1rem !important;
    }
}
</style>