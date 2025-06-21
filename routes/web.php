<?php
require_once __DIR__ . '/../core/Router.php';

// ==========================
// ROTAS PÚBLICAS (Usuário)
// ==========================

// Página sobre o sistema
Router::add('GET', 'about', 'Public\ResponderController@about');
Router::add('GET', 'about/{slug}', 'Public\ResponderController@about');

// Formulário de resposta da clínica
Router::add('GET', 'user/responder/{slug}', 'Public\ResponderController@responder');

// Salvar respostas
Router::add('POST', 'user/salvar-respostas', 'Public\RespostaController@salvar');
Router::add('POST', 'user/salvar-respostas/{clinica}/{cliente}', 'Public\RespostaController@salvar');

// Página de agradecimento
Router::add('GET', 'obrigado', 'Public\RespostaController@obrigado');

// ==========================
// ROTAS DE AUTENTICAÇÃO
// ==========================

// Login
Router::add('GET', 'admin/login', 'Auth\LoginController@login');
Router::add('POST', 'admin/login/process', 'Auth\LoginController@processLogin');
Router::add('GET', 'admin/logout', 'Auth\LoginController@logout');

// Cadastro de administradores
Router::add('GET', 'admins/showRegisterForm', 'Admin\RegisterController@showRegisterForm');
Router::add('POST', 'admins/processRegister', 'Admin\RegisterController@processRegister');

// Alterar credenciais
Router::add('GET', 'admin/{username}/editCredentials', 'Auth\LoginController@editCredentials');
Router::add('POST', 'admin/updateCredentials', 'Auth\LoginController@updateCredentials');

// ==========================
// ROTAS ADMINISTRATIVAS
// ==========================

// Dashboard principal
Router::add('GET', 'admin/{username}/home', 'Admin\DashboardController@dashboard');

// Busca no dashboard
Router::add('GET', 'admin/search', 'Admin\DashboardController@search');
Router::add('POST', 'admin/search', 'Admin\DashboardController@search');

// ==========================
// GESTÃO DE PERGUNTAS
// ==========================

Router::add('GET', 'admin/perguntas', 'Admin\PerguntaController@index');
Router::add('POST', 'admin/perguntas/salvar', 'Admin\PerguntaController@salvar');
Router::add('GET', 'admin/perguntas/excluir/{id}', 'Admin\PerguntaController@excluir');
Router::add('POST', 'admin/perguntas/excluir/{id}', 'Admin\PerguntaController@excluir');
Router::add('POST', 'admin/perguntas/usar-modelo', 'Admin\PerguntaController@usarModelo');

// ==========================
// GESTÃO DE FICHAS
// ==========================

// Lista fichas
Router::add('GET', 'admin/fichas', 'Admin\FichaController@index');

// Ver ficha específica
Router::add('GET', 'admin/ficha/{clinica}/{pessoa}/{id}', 'Admin\FichaController@verFicha');

// Ações com fichas
Router::add('POST', 'admin/ficha/excluir/{id}', 'Admin\FichaController@excluir');
Router::add('POST', 'admin/ficha/restaurar/{id}', 'Admin\FichaController@restaurar');
Router::add('GET', 'admin/ficha/exportar-pdf/{id}', 'Admin\FichaController@exportarPDF');
Router::add('POST', 'admin/ficha/observacao/{id}', 'Admin\FichaController@adicionarObservacao');

// Buscar fichas - TODAS AS VARIAÇÕES
Router::add('GET', 'admin/fichas/buscar', 'Admin\FichaController@buscar');
Router::add('POST', 'admin/fichas/buscar', 'Admin\FichaController@buscar');
Router::add('GET', 'admin/ficha/buscar', 'Admin\FichaController@buscar'); // Alternativa
Router::add('POST', 'admin/ficha/buscar', 'Admin\FichaController@buscar'); // Alternativa

// Lixeira de fichas
Router::add('GET', 'admin/fichas/lixeira', 'Admin\FichaController@lixeira');

// Paginação manual (se implementar no futuro)
Router::add('GET', 'admin/fichas/page/{page}', 'Admin\FichaController@indexPaginado');

// ==========================
// PERFIL DO ADMINISTRADOR
// ==========================

// Ver perfil
Router::add('GET', 'admin/{username}/perfil', 'Admin\ProfileController@view');

// Editar perfil
Router::add('GET', 'admin/{username}/perfil/editar', 'Admin\ProfileController@edit');
Router::add('POST', 'admin/{username}/perfil/update', 'Admin\ProfileController@update');

// Atualizar campo específico (AJAX)
Router::add('POST', 'admin/perfil/update-field', 'Admin\ProfileController@updateField');

// ==========================
// ROTAS DE FALLBACK E REDIRECIONAMENTOS
// ==========================

// Redireciona / para login
Router::add('GET', '', function () {
    header('Location: ' . BASE_URL . 'admin/login');
    exit;
});

Router::add('GET', '/', function () {
    header('Location: ' . BASE_URL . 'admin/login');
    exit;
});

// Redireciona /admin para login
Router::add('GET', 'admin', function () {
    header('Location: ' . BASE_URL . 'admin/login');
    exit;
});

// ==========================
// ROTAS DE DESENVOLVIMENTO/DEBUG (Remover em produção)
// ==========================

// Rota de teste para verificar se o sistema está funcionando
Router::add('GET', 'test', function () {
    echo json_encode([
        'status' => 'success',
        'message' => 'Sistema funcionando!',
        'timestamp' => date('Y-m-d H:i:s'),
        'base_url' => BASE_URL
    ]);
});

// Rota para limpar cache/sessão (útil para desenvolvimento)
Router::add('GET', 'clear-session', function () {
    session_start();
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Sessão limpa']);
});

// ==========================
// TRATAMENTO DE ROTAS ANTIGAS (Compatibilidade)
// ==========================

// Redirecionamentos para compatibilidade com rotas antigas
Router::add('GET', 'admins/{username}/editCredentials', function ($username) {
    header('Location: ' . BASE_URL . 'admin/' . $username . '/editCredentials');
    exit;
});

Router::add('GET', 'admin/home', function () {
    if (isset($_SESSION['admin_username'])) {
        header('Location: ' . BASE_URL . 'admin/' . $_SESSION['admin_username'] . '/home');
    } else {
        header('Location: ' . BASE_URL . 'admin/login');
    }
    exit;
});
