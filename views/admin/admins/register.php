<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Painel Administrativo</title>

    <!-- Ícones -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?= BASE_URL ?>/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/img/favicon.png">

    <!-- Fonts e ícones -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet">

    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/soft-ui-dashboard.css?v=1.1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-dt/css/jquery.dataTables.min.css">

    <!-- Analytics (remova ou configure o domínio corretamente) -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>

    <style>
    :root {
        --primary-color: #2c7873;
        --primary-hover: #1c5954;
        --secondary-color: #52de97;
        --light-color: #f7f9fc;
        --dark-color: #2c3e50;
        --danger-color: #e74c3c;
        --success-color: #2ecc71;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4efe9 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .navbar-brand {
        font-weight: 700;
        color: var(--primary-color);
    }

    .navbar {
        background-color: white;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
    }

    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        font-weight: 600;
        padding: 1.8rem 0;
        border-bottom: none;
        position: relative;
        overflow: hidden;

        h3 {
            color: #fff;
        }
    }

    .card-header::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -75px;
        right: -75px;
    }

    .card-header::after {
        content: '';
        position: absolute;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        bottom: -50px;
        left: -50px;
    }

    .card-header h3 {
        position: relative;
        z-index: 1;
        font-weight: 700;
        font-size: 1.75rem;
    }

    .card-body {
        padding: 2.5rem;
    }

    .form-label {
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .form-control,
    .input-group-text {
        padding: 0.8rem 1rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-control {
        background-color: #f9f9f9;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(44, 120, 115, 0.2);
    }

    .input-group-text {
        background-color: #f0f0f0;
        color: var(--primary-color);
        border-right: none;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group:focus-within .input-group-text {
        border-color: var(--primary-color);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 0.8rem 1.5rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(44, 120, 115, 0.3);
    }

    .toggle-password {
        border: none;
        background-color: transparent;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        color: #6c757d;
        transition: color 0.2s;
    }

    .toggle-password:hover {
        color: var(--primary-color);
    }

    #passwordStrength {
        height: 5px;
        border-radius: 3px;
        margin-top: 10px;
        transition: all 0.4s;
    }

    .password-feedback {
        font-size: 0.85rem;
    }

    .password-feedback li {
        margin-bottom: 0.3rem;
        transition: all 0.3s;
    }

    .password-feedback i.valid {
        color: var(--success-color);
    }

    .password-feedback i.invalid {
        color: var(--danger-color);
    }

    .create-account-btn {
        margin-top: 1.5rem;
        padding: 1rem;
        font-size: 1.1rem;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .create-account-btn::after {
        content: '';
        position: absolute;
        background: rgba(255, 255, 255, 0.2);
        width: 5px;
        height: 100%;
        top: 0;
        left: -50px;
        transform: skewX(-45deg);
        transition: all 1s;
        z-index: -1;
    }

    .create-account-btn:hover::after {
        width: 150%;
        left: -10%;
    }

    .login-link {
        color: var(--primary-color);
        font-weight: 600;
        transition: all 0.3s;
    }

    .login-link:hover {
        color: var(--primary-hover);
        text-decoration: underline !important;
    }

    @media (max-width: 767.98px) {
        .card-body {
            padding: 1.5rem;
        }

        .card-header h3 {
            font-size: 1.5rem;
        }
    }

    /* Animações */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }

    .form-group:nth-child(1) {
        animation-delay: 0.1s;
    }

    .form-group:nth-child(2) {
        animation-delay: 0.2s;
    }

    .form-group:nth-child(3) {
        animation-delay: 0.3s;
    }

    .form-group:nth-child(4) {
        animation-delay: 0.4s;
    }

    .form-group:nth-child(5) {
        animation-delay: 0.5s;
    }

    .form-group:nth-child(6) {
        animation-delay: 0.6s;
    }

    .footer {
        margin-top: auto;
        background-color: white;
        color: var(--dark-color);
        padding: 1rem 0;
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.05);
    }
    </style>
</head>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg  ">


    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3><i class="fas fa-user-plus me-2"></i>Criar Conta</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)) : ?>
                        <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            Swal.fire({
                                icon: "error",
                                title: "Erro!",
                                text: "<?= $error ?>",
                                confirmButtonColor: "#e74c3c"
                            });
                        });
                        </script>
                        <?php endif; ?>

                        <form id="registerForm" method="POST" action="<?= BASE_URL ?>admins/processRegister"
                            autocomplete="off">

                            <?php $form_data = $form_data ?? []; ?>

                            <div class="form-group mb-4">
                                <label for="username" class="form-label">Nome de usuário</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="username" id="username"
                                        value="<?= htmlspecialchars($form_data['username'] ?? '') ?>"
                                        placeholder="Escolha um nome de usuário" autocomplete="off" required>
                                </div>
                                <small class="form-text text-muted">Use 5-30 caracteres: letras, números, hífen (-) e
                                    underline (_).</small>
                            </div>

                            <div class="form-group mb-4">
                                <label for="nome_completo" class="form-label">Nome completo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                    <input type="text" class="form-control" name="nome_completo" id="nome_completo"
                                        value="<?= htmlspecialchars($form_data['nome_completo'] ?? '') ?>"
                                        placeholder="Digite seu nome completo" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="nome_clinica" class="form-label">Nome da sua clinica</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="nome_clinica" id="nome_clinica"
                                        value="<?= htmlspecialchars($form_data['nome_clinica'] ?? '') ?>"
                                        placeholder="Nome da sua clinica" autocomplete="off" required>
                                </div>
                                <small class="form-text text-muted">Use apenas letras</small>
                            </div>
                            <div class="form-group mb-4">
                                <label for="razao_social" class="form-label">Razão social</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" name="razao_social" id="razao_social"
                                        value="<?= htmlspecialchars($form_data['razao_social'] ?? '') ?>"
                                        placeholder="Razão social" autocomplete="off" required>
                                </div>
                                <small class="form-text text-muted">Use apenas letras</small>
                            </div>
                            <div class="form-group mb-4">
                                <label for="cnpj" class="form-label">CNPJ</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" name="cnpj" id="cnpj"
                                        value="<?= htmlspecialchars($form_data['cnpj'] ?? '') ?>"
                                        placeholder="00.000.000/0001-00" autocomplete="off" required>
                                </div>
                                <small class="form-text text-muted">Informe um CNPJ válido</small>
                            </div>

                            <div class="form-group mb-4">
                                <label for="email" class="form-label">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" id="email"
                                        value="<?= htmlspecialchars($form_data['email'] ?? '') ?>"
                                        placeholder="Digite seu e-mail" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label for="telefone" class="form-label">Telefone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" name="telefone" id="telefone"
                                        value="<?= htmlspecialchars($form_data['telefone'] ?? '') ?>"
                                        placeholder="(00) 00000-0000" autocomplete="off" required>
                                </div>
                            </div>


                            <div class="form-group mb-4">
                                <label for="password" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Digite sua senha" autocomplete="new-password" required>
                                    <button type="button" class="btn toggle-password"
                                        onclick="togglePassword('password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>

                                <div id="passwordStrength" class="bg-secondary bg-opacity-25"></div>

                                <ul class="list-unstyled password-feedback mt-2">
                                    <li id="length"><i class="fas fa-times me-2 invalid"></i>Mínimo de 8 caracteres</li>
                                    <li id="uppercase"><i class="fas fa-times me-2 invalid"></i>Pelo menos 1 letra
                                        maiúscula</li>
                                    <li id="lowercase"><i class="fas fa-times me-2 invalid"></i>Pelo menos 1 letra
                                        minúscula</li>
                                    <li id="number"><i class="fas fa-times me-2 invalid"></i>Pelo menos 1 número</li>
                                    <li id="special"><i class="fas fa-times me-2 invalid"></i>Pelo menos 1 caractere
                                        especial (!@#$...)</li>
                                </ul>
                            </div>

                            <div class="form-group mb-4">
                                <label for="confirm_password" class="form-label">Confirmar senha</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                    <input type="password" class="form-control" name="confirm_password"
                                        id="confirm_password" placeholder="Confirme sua senha"
                                        autocomplete="new-password" required>
                                    <button type="button" class="btn toggle-password"
                                        onclick="togglePassword('confirm_password', this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="form-text mt-2">
                                    <i class="fas fa-times me-2 text-danger"></i>As senhas devem ser iguais
                                </div>
                            </div>

                            <div class="form-group form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">
                                    Eu concordo com os <a href="#" class="text-decoration-none">termos de uso</a> e <a
                                        href="#" class="text-decoration-none">política de privacidade</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 create-account-btn">
                                <i class="fas fa-user-plus me-2"></i>Criar minha conta
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">Já possui uma conta? <a href="<?= BASE_URL ?>admin/login"
                                    class="login-link text-decoration-none">Entrar</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {

        <?php if (!empty($error)) : ?>
        Swal.fire({
            icon: "error",
            title: "Erro!",
            html: <?= json_encode($error) ?>, // ← permite usar <span>, <b>, etc.
            confirmButtonColor: "#e74c3c"
        });

        <?php endif; ?>

        const telefoneInput = document.getElementById('telefone');
        const form = document.getElementById("registerForm");
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const passwordStrength = document.getElementById('passwordStrength');

        // Máscara telefone
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);

            if (value.length > 2) {
                value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            }
            if (value.length > 10) {
                value = value.substring(0, 10) + '-' + value.substring(10);
            }

            e.target.value = value;
        });

        // Validação visual senha
        const rules = {
            length: /.{8,}/,
            uppercase: /[A-Z]/,
            lowercase: /[a-z]/,
            number: /\d/,
            special: /[^A-Za-z0-9]/
        };

        function updateIcon(elementId, isValid) {
            const li = document.getElementById(elementId);
            const icon = li.querySelector("i");

            if (isValid) {
                icon.className = "fas fa-check me-2 valid";
                li.style.color = "#2ecc71";
            } else {
                icon.className = "fas fa-times me-2 invalid";
                li.style.color = "";
            }
        }

        function validatePassword() {
            const value = passwordInput.value;
            let score = 0;

            for (const rule in rules) {
                const isValid = rules[rule].test(value);
                updateIcon(rule, isValid);
                if (isValid) score += 20;
            }

            passwordStrength.style.width = score + "%";

            if (score === 0) {
                passwordStrength.className = "bg-secondary bg-opacity-25";
            } else if (score <= 40) {
                passwordStrength.className = "bg-danger";
            } else if (score <= 80) {
                passwordStrength.className = "bg-warning";
            } else {
                passwordStrength.className = "bg-success";
            }
        }

        function checkPasswordMatch() {
            const matchInfo = document.getElementById('passwordMatch');
            const isMatch = confirmInput.value === passwordInput.value && passwordInput.value !== "";

            if (isMatch) {
                matchInfo.innerHTML = '<i class="fas fa-check me-2 text-success"></i>As senhas estão iguais';
                matchInfo.style.color = "#2ecc71";
            } else {
                matchInfo.innerHTML = '<i class="fas fa-times me-2 text-danger"></i>As senhas devem ser iguais';
                matchInfo.style.color = "#e74c3c";
            }
        }

        passwordInput.addEventListener("input", () => {
            validatePassword();
            checkPasswordMatch();
        });

        confirmInput.addEventListener("input", checkPasswordMatch);

        // Alternar senha visível
        window.togglePassword = function(inputId, trigger) {
            const input = document.getElementById(inputId);
            const icon = trigger.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.className = "fas fa-eye-slash";
            } else {
                input.type = "password";
                icon.className = "fas fa-eye";
            }
        };

        // Envio com SweetAlert e submit padrão
        form.addEventListener("submit", function(e) {
            e.preventDefault(); // bloqueia envio temporariamente

            const username = form.querySelector("#username").value.trim();
            const email = form.querySelector("#email").value.trim();
            const telefone = form.querySelector("#telefone").value.trim();
            const password = form.querySelector("#password").value;
            const confirmPassword = form.querySelector("#confirm_password").value;

            // Validações simples
            if (!/^[a-zA-Z0-9_-]{5,30}$/.test(username)) {
                return Swal.fire("Nome de usuário inválido",
                    "Use apenas letras, números, hífen (-) e underline (_).", "warning");
            }

            if (!validateEmail(email)) {
                return Swal.fire("E-mail inválido", "Digite um e-mail válido.", "warning");
            }

            if (telefone.replace(/\D/g, '').length < 10) {
                return Swal.fire("Telefone inválido", "Digite um número de telefone válido com DDD.",
                    "warning");
            }

            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&._-])[A-Za-z\d@$!%*?&._-]{8,}$/.test(
                    password)) {
                return Swal.fire("Senha fraca",
                    "A senha deve conter letras maiúsculas, minúsculas, números e símbolo.",
                    "warning");
            }

            if (password !== confirmPassword) {
                return Swal.fire("Senhas diferentes", "As senhas não coincidem.", "warning");
            }

            // Agora mostra "Criando conta..." e envia
            Swal.fire({
                title: 'Criando sua conta...',
                text: 'Por favor, aguarde',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => {
                        form.submit();
                    }, 1000);
                }
            });
        });

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email.toLowerCase());
        }
    });
    </script>