<?php
// Obtém dados compartilhados usando ViewHelper
use App\Lib\ViewHelper;

$sharedData = ViewHelper::getSharedData();
$ficha_id = $sharedData['ficha_id'];
$slug_clinica = $sharedData['slug_clinica'];
$admin_id = $sharedData['admin_id'];
$admin_username = $_SESSION['admin_username'] ?? 'admin';
?>

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
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-10 my-3 fixed-start"
        id="sidenav-main">
        <div class="sidenav-header">
            <a class="navbar-brand m-0" href="<?= BASE_URL ?>admin/<?= $admin_username ?>/home">
                <img src="<?= BASE_URL ?>/img/logo.jpg" class="navbar-brand-img" alt="Logo DF Massoterapia">
                <span class="ms-1 font-weight-bold">DF Massoterapia</span>
            </a>
        </div>

        <?php $activePage = basename($_SERVER['REQUEST_URI'], ".php"); ?>

        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/home') !== false) ? 'active' : ''; ?>"
                        href="<?= BASE_URL ?>admin/<?= $admin_username ?>/home">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="nav-link-text ms-1">Home</span>
                    </a>
                </li>

                <!-- Cadastrar Perguntas -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/perguntas') !== false) ? 'active' : ''; ?>"
                        href="<?= BASE_URL ?>admin/perguntas">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span class="nav-link-text ms-1">Cadastrar perguntas</span>
                    </a>
                </li>

                <!-- Listar Fichas -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/fichas') !== false) ? 'active' : ''; ?>"
                        href="<?= BASE_URL ?>admin/fichas">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="nav-link-text ms-1">Listar fichas</span>
                    </a>
                </li>

                <!-- Link da Ficha de Perguntas (para copiar) -->
                <?php
                $linkFicha = BASE_URL . "user/responder/{$slug_clinica}";
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-url="<?= $linkFicha ?>" onclick="copiarTexto(event)">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-copy"></i>
                        </div>
                        <span class="nav-link-text ms-1">Copiar link da ficha</span>
                    </a>
                </li>

                <!-- Perfil -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/perfil') !== false) ? 'active' : ''; ?>"
                        href="<?= BASE_URL ?>admin/<?= $admin_username ?>/perfil">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="nav-link-text ms-1">Meu perfil</span>
                    </a>
                </li>

                <!-- Alterar Senha - CORREÇÃO: URL atualizada -->
                <li class="nav-item">
                    <a class="nav-link <?= (strpos($_SERVER['REQUEST_URI'], '/editCredentials') !== false) ? 'active' : ''; ?>"
                        href="<?= BASE_URL ?>admin/<?= $admin_username ?>/editCredentials">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-key"></i>
                        </div>
                        <span class="nav-link-text ms-1">Alterar senha</span>
                    </a>
                </li>

                <!-- Cadastrar Novo Admin -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>admins/showRegisterForm" target="_blank">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <span class="nav-link-text ms-1">Cadastrar admin</span>
                    </a>
                </li>

                <!-- Sair -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>admin/logout">
                        <div
                            class="icon icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <span class="nav-link-text ms-1">Sair</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <script>
        function copiarTexto(event) {
            event.preventDefault();
            const url = event.currentTarget.getAttribute('data-url');

            if (!url) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Link não disponível.',
                });
                return;
            }

            navigator.clipboard.writeText(url)
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Link copiado!',
                        text: 'Você pode enviar este link ao cliente para preenchimento da ficha.',
                        timer: 3000,
                        showConfirmButton: false,
                        position: 'top'
                    });
                })
                .catch((err) => {
                    console.error("Erro ao copiar: ", err);
                    // Fallback para navegadores que não suportam clipboard API
                    const textArea = document.createElement("textarea");
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        Swal.fire({
                            icon: 'success',
                            title: 'Link copiado!',
                            text: 'Você pode enviar este link ao cliente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Não foi possível copiar o link automaticamente. Copie manualmente: ' + url,
                        });
                    }
                    document.body.removeChild(textArea);
                });
        }
    </script>