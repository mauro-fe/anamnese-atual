<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

    <?php if (isset($_SESSION['error'])): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: '<?= htmlspecialchars($_SESSION['error']); ?>',
            position: 'top'
        });
    });
    </script>
    <?php unset($_SESSION['error']); // Remove a mensagem após exibir 
        ?>
    <?php endif; ?>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-sm border-radius-xl bg-white" id="navbarBlur"
        navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm">
                        <a class="opacity-5 text-dark" href="javascript:;">Página</a>
                    </li>
                    <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Pesquisa</li>
                </ol>
                <h6 class="font-weight-bolder mb-0">Pesquisar</h6>
            </nav>


            <!-- Botão de Logout dentro da Navbar -->
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                        <button class="d-sm-inline d-none btn btn-primary mt-3"><i
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
    </nav>
    <!-- End Navbar -->

    <!-- Formulário de Pesquisa Centralizado -->
    <div class="d-flex justify-content-center mt-10">
        <form action="<?= BASE_URL ?>admin/ficha/search" method="post" class="d-flex w-80 ">
            <div class="input-group ">
                <span class="input-group-text text-body">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </span>
                <input type="text" name="fakeuser" style="position:absolute; top:-9999px; left:-9999px;"
                    autocomplete="off">
                <input type="text" name="username" class="form-control" placeholder="Pesquisar cliente..."
                    autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-primary ms-2 text-center">Pesquisar</button>
        </form>
    </div>

</main>