<style>
    :root {
        --primary-color: #6366f1;
        --secondary-color: #8b5cf6;
        --success-color: #10b981;
        --info-color: #3b82f6;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
    }

    body {
        background-color: #f9fafb;
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        min-height: 100vh;
    }

    .main-content {
        padding: 20px;
    }

    .header-banner {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .pergunta-card {
        background-color: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 5px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .pergunta-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .pergunta-titulo {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .pergunta-icon {
        background-color: var(--primary-color);
        color: white;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 10px;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .option-item {
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .option-item:hover {
        background-color: rgba(99, 102, 241, 0.1);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.5);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-2px);
    }

    .complemento-texto {
        border-top: 1px dashed #e2e8f0;
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .progress-container {
        position: sticky;
        top: 0;
        background-color: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 100;
        margin-bottom: 2rem;
    }

    .progress {
        height: 0.5rem;
        background-color: #e2e8f0;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    }

    .obrigatorio-badge {
        background-color: var(--danger-color);
        color: white;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-left: 0.5rem;
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

    .footer {
        text-align: center;
        padding: 2rem 0;
        color: #6b7280;
    }

    /* Estilos para os diferentes tipos de perguntas */
    .card-texto {
        border-left-color: #3b82f6;
    }

    .card-textarea {
        border-left-color: #8b5cf6;
    }

    .card-checkbox {
        border-left-color: #10b981;
    }

    .card-radio {
        border-left-color: #f59e0b;
    }

    .card-select {
        border-left-color: #ef4444;
    }
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container mt-4">
        <!-- Header Banner -->
        <div class="header-banner fade-in">
            <h2><i class="fas fa-clipboard-list me-2"></i> Formulário de Perguntas</h2>
            <p class="mb-0">Por favor, responda cuidadosamente às perguntas abaixo.</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-container fade-in">
            <div class="d-flex justify-content-between mb-1">
                <span>Progresso do formulário</span>
                <span id="progress-text">0%</span>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </div>

        <!-- Form -->
        <form id="perguntasForm" action="<?= BASE_URL ?>user/salvar-respostas" method="POST" class="fade-in">
            <!-- Campo oculto com admin_id -->
            <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin_id) ?>">

            <?php
            $contador = 1;
            $totalPerguntas = count($perguntas);
            foreach ($perguntas as $p):
                $tipoIcone = [
                    'texto' => 'fas fa-font',
                    'textarea' => 'fas fa-align-left',
                    'checkbox' => 'fas fa-check-square',
                    'radio' => 'fas fa-dot-circle',
                    'select' => 'fas fa-caret-square-down',
                    'date' => 'fas fa-calendar-alt',
                    'tel' => 'fas fa-phone',
                    'email' => 'fas fa-envelope',
                    'number' => 'fas fa-hashtag'
                ];
                $icone = isset($tipoIcone[$p->tipo]) ? $tipoIcone[$p->tipo] : 'fas fa-question';
                $cardClass = 'card-' . $p->tipo;
                $opcoes = !empty($p->opcoes) ? array_map('trim', explode(',', $p->opcoes)) : [];
                $obrigatorio = !empty($p->obrigatorio);
            ?>

                <div class="pergunta-card <?= $cardClass ?>" data-pergunta-id="<?= $p->id ?>">
                    <h5 class="pergunta-titulo">
                        <span class="pergunta-icon"><i class="<?= $icone ?>"></i></span>
                        <?= htmlspecialchars($p->pergunta) ?>
                        <?php if ($obrigatorio): ?>
                            <span class="obrigatorio-badge"><i class="fas fa-asterisk me-1"></i>Obrigatório</span>
                        <?php endif; ?>
                    </h5>

                    <div class="pergunta-conteudo">
                        <?php if (in_array($p->tipo, ['texto', 'email', 'tel', 'number', 'date'])): ?>
                            <div class="input-group">
                                <span class="input-group-text"><i class="<?= $icone ?>"></i></span>
                                <input type="<?= $p->tipo === 'texto' ? 'text' : $p->tipo ?>" class="form-control"
                                    name="respostas[<?= $p->id ?>]" placeholder="<?= htmlspecialchars($p->placeholder ?? '') ?>"
                                    <?= $obrigatorio ? 'required' : '' ?>>
                            </div>

                        <?php elseif ($p->tipo == 'textarea'): ?>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-paragraph"></i></span>
                                <textarea class="form-control" name="respostas[<?= $p->id ?>]" rows="3"
                                    placeholder="<?= htmlspecialchars($p->placeholder ?? '') ?>"
                                    <?= $obrigatorio ? 'required' : '' ?>></textarea>
                            </div>

                        <?php elseif ($p->tipo == 'checkbox'): ?>
                            <div class="row">
                                <?php foreach ($opcoes as $op): ?>
                                    <div class="col-md-6">
                                        <div class="option-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="respostas[<?= $p->id ?>][]"
                                                    value="<?= htmlspecialchars($op) ?>" id="check_<?= $p->id ?>_<?= md5($op) ?>">
                                                <label class="form-check-label" for="check_<?= $p->id ?>_<?= md5($op) ?>">
                                                    <?= htmlspecialchars($op) ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($p->tipo == 'radio'): ?>
                            <div class="row">
                                <?php foreach ($opcoes as $op): ?>
                                    <div class="col-md-6">
                                        <div class="option-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="respostas[<?= $p->id ?>]"
                                                    value="<?= htmlspecialchars($op) ?>" id="radio_<?= $p->id ?>_<?= md5($op) ?>"
                                                    <?= $obrigatorio ? 'required' : '' ?>>
                                                <label class="form-check-label" for="radio_<?= $p->id ?>_<?= md5($op) ?>">
                                                    <?= htmlspecialchars($op) ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($p->tipo == 'select'): ?>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <select name="respostas[<?= $p->id ?>]" class="form-select"
                                    <?= $obrigatorio ? 'required' : '' ?>>
                                    <option value="">Selecione uma opção</option>
                                    <?php foreach ($opcoes as $op): ?>
                                        <option value="<?= htmlspecialchars($op) ?>"><?= htmlspecialchars($op) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <!-- Campo de resposta adicional (com placeholder customizado) -->
                        <?php if (!empty($p->complemento_texto)): ?>
                            <div class="complemento-texto mt-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
                                    <input type="text" name="respostas_texto[<?= $p->id ?>]" class="form-control"
                                        placeholder="<?= htmlspecialchars($p->placeholder ?? 'Descreva mais (opcional)') ?>">
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($p->tipo == 'assinatura'): ?>
                            <label class="form-label"><?= htmlspecialchars($p->pergunta) ?></label>
                            <input type="text" class="form-control" name="respostas[<?= $p->id ?>]"
                                placeholder="<?= htmlspecialchars($p->placeholder ?? 'Digite sua assinatura') ?>"
                                <?= $obrigatorio ? 'required' : '' ?>>
                        <?php endif; ?>
                    </div>

                    <div class="text-muted mt-2 small">
                        <i class="fas fa-info-circle me-1"></i> Pergunta <?= $contador ?> de <?= $totalPerguntas ?>
                    </div>
                </div>
            <?php
                $contador++;
            endforeach;
            ?>

            <div class="d-grid gap-2 mb-5">
                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane me-2"></i> Enviar Respostas
                </button>
            </div>
        </form>


        <div class="footer">
            <p>© <?= date('Y') ?> - Formulário de Perguntas</p>
        </div>
    </div>
</main>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.12/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const perguntasForm = document.getElementById('perguntasForm');
        const perguntasCards = document.querySelectorAll('.pergunta-card');
        const progressBar = document.querySelector('.progress-bar');
        const progressText = document.getElementById('progress-text');
        const submitBtn = document.getElementById('submitBtn');

        // Atualiza o progresso do formulário
        function atualizarProgresso() {
            const totalPerguntas = perguntasCards.length;
            let perguntasRespondidas = 0;

            perguntasCards.forEach(card => {
                const perguntaId = card.getAttribute('data-pergunta-id');
                const inputs = card.querySelectorAll('input, textarea, select');
                let respondida = false;

                inputs.forEach(input => {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        if (input.checked) {
                            respondida = true;
                        }
                    } else if (input.value.trim() !== '') {
                        respondida = true;
                    }
                });

                // Destaca visualmente a pergunta respondida
                if (respondida) {
                    card.classList.add('border-success');
                    perguntasRespondidas++;
                } else {
                    card.classList.remove('border-success');
                }
            });

            const percentual = Math.round((perguntasRespondidas / totalPerguntas) * 100);
            progressBar.style.width = percentual + '%';
            progressBar.setAttribute('aria-valuenow', percentual);
            progressText.textContent = percentual + '%';

            // Altera a cor da barra de progresso com base no percentual
            if (percentual < 30) {
                progressBar.className = 'progress-bar bg-danger';
            } else if (percentual < 70) {
                progressBar.className = 'progress-bar bg-warning';
            } else {
                progressBar.className = 'progress-bar bg-success';
            }
        }

        // Adiciona listeners a todos os inputs para atualizar o progresso
        document.querySelectorAll('input, textarea, select').forEach(element => {
            element.addEventListener('change', atualizarProgresso);
            element.addEventListener('input', atualizarProgresso);
        });

        // Scroll suave para a próxima pergunta
        function scrollToNextQuestion(currentCard) {
            const nextCard = currentCard.nextElementSibling;
            if (nextCard && nextCard.classList.contains('pergunta-card')) {
                nextCard.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Adiciona comportamento ao pressionar Enter nos campos de texto
        document.querySelectorAll('input[type="text"], textarea').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const currentCard = this.closest('.pergunta-card');
                    scrollToNextQuestion(currentCard);
                }
            });
        });

        // Confirmação antes de enviar
        perguntasForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Verificar campos obrigatórios
            let todosPreenchidos = true;
            const camposObrigatorios = perguntasForm.querySelectorAll('[required]');

            camposObrigatorios.forEach(campo => {
                if (!campo.value.trim()) {
                    todosPreenchidos = false;
                    campo.classList.add('is-invalid');
                } else {
                    campo.classList.remove('is-invalid');
                }
            });

            if (!todosPreenchidos) {
                Swal.fire({
                    title: 'Atenção!',
                    text: 'Por favor, preencha todos os campos obrigatórios.',
                    icon: 'warning',
                    confirmButtonColor: '#6366f1'
                });

                // Scroll para o primeiro campo não preenchido
                const primeiroInvalido = perguntasForm.querySelector('.is-invalid');
                if (primeiroInvalido) {
                    primeiroInvalido.closest('.pergunta-card').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                return;
            }

            // Confirmar envio
            Swal.fire({
                title: 'Enviar respostas?',
                text: 'Confirme para enviar suas respostas. Após o envio, não será possível fazer alterações.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Sim, enviar',
                cancelButtonText: 'Revisar respostas'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading enquanto envia
                    Swal.fire({
                        title: 'Enviando...',
                        html: 'Por favor, aguarde enquanto suas respostas são enviadas.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Enviar o formulário
                    perguntasForm.submit();
                }
            });
        });

        // Tooltip para campos obrigatórios
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('.obrigatorio-badge'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                title: 'Este campo precisa ser preenchido'
            });
        });

        // Mostrar toast de boas-vindas
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
            title: 'Formulário carregado, vamos começar!'
        });

        // Inicializa o progresso
        atualizarProgresso();
    });
</script>