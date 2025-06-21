<style>
    :root {
        --primary-color: #12453e;
        --secondary-color: #83a39f;
        --text-dark: #333;
        --text-light: #fff;
        --error-color: #dc3545;
        --success-color: #28a745;
    }

    body {
        margin: 0;
        font-family: 'Poppins', 'Arial', sans-serif;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));

    }


    .card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        transition: transform 0.3s ease;
        max-width: 400px;
        margin: 0 auto;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background: var(--primary-color);
        color: var(--text-light);
        padding: 25px 20px;
        text-align: center;
        position: relative;
    }

    .card-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .card-header p {
        margin: 10px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .card-body {
        padding: 30px;
        background-color: #fff;

    }

    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-dark);
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 15px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(18, 69, 62, 0.1);
        outline: none;
        background-color: #fff;
    }

    .input-icon {
        position: absolute;
        top: 40px;
        right: 15px;
        color: #999;
        cursor: pointer;
    }

    .btn {
        width: 100%;
        padding: 15px;
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 30px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(18, 69, 62, 0.2);
    }

    .btn:hover {
        background: #0d3430;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(18, 69, 62, 0.3);
    }

    .btn:active {
        transform: translateY(0);
    }

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .remember-forgot a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s;
    }

    .remember-forgot a:hover {
        color: #0d3430;
        text-decoration: underline;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
    }

    .checkbox-wrapper input {
        margin-right: 8px;
    }

    .error-message {
        color: var(--error-color);
        background-color: rgba(220, 53, 69, 0.1);
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        border-left: 4px solid var(--error-color);
    }

    .brand-logo {
        margin-bottom: 15px;
        font-size: 32px;
        color: #fff;
    }

    footer {
        color: #fff;
    }

    @media (max-width: 576px) {
        .container {
            padding: 0 10px;
        }

        .card-body {
            padding: 20px;
        }
    }

    /* Password visibility toggle animation */
    .password-toggle {
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .password-toggle:hover {
        opacity: 0.7;
    }

    /* Smooth animation for form elements */
    .form-control,
    .btn {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3>Seja Bem-Vindo</h3>
                    <p>Informe seu usuário e sua senha para acessar o sistema</p>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>admin/login/process" method="POST" id="loginForm">
                        <div class="form-group">
                            <label for="login">Usuário ou E-mail</label>
                            <input type="text" id="login" name="login" class="form-control"
                                placeholder="Digite seu usuário ou e-mail" autocomplete="off" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Senha</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Digite sua senha" autocomplete="off" required>
                            <span class="input-icon password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>

                        <?php if (!empty($error)) { ?>
                            <div class="error-message">
                                <?= $error ?>
                            </div>
                        <?php } ?>

                        <div class="remember-forgot">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Lembrar-me</label>
                            </div>
                            <a href="#">Esqueceu a senha?</a>
                        </div>

                        <button type="submit" class="btn">
                            Acessar Sistema
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Font Awesome for icons -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

        <script>
            // Toggle password visibility
            function togglePassword() {
                const passwordField = document.getElementById('password');
                const toggleIcon = document.getElementById('toggleIcon');

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            }

            // Form validation with smooth effect
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const loginField = document.getElementById('login');
                const passwordField = document.getElementById('password');

                if (!loginField.value.trim() || !passwordField.value.trim()) {
                    e.preventDefault();

                    if (!loginField.value.trim()) {
                        loginField.style.borderColor = 'var(--error-color)';
                        loginField.focus();
                    }

                    if (!passwordField.value.trim()) {
                        passwordField.style.borderColor = 'var(--error-color)';
                        if (loginField.value.trim()) {
                            passwordField.focus();
                        }
                    }
                }
            });

            // Remove error styling on input
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.style.borderColor = '';
                });
            });
        </script>