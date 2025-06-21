<?php
// Configurações do banco de dados
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "anamnese");

// Configuração da base URL
define("BASE_URL", "http://localhost/anamnese/public/");


// Incluir o autoload do Composer (gerado pelo Composer quando você instala pacotes)
require_once __DIR__ . '/../vendor/autoload.php'; // Ajuste o caminho conforme necessário

use Illuminate\Database\Capsule\Manager as Capsule;

// Configuração do banco de dados com Eloquent
$capsule = new Capsule;

// Adicionando a conexão com o banco de dados
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => DB_HOST,
    'database'  => DB_NAME,
    'username'  => DB_USER,
    'password'  => DB_PASSWORD,
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '', // Prefixo de tabela (caso tenha)
]);

// Iniciar o Eloquent ORM
$capsule->setAsGlobal();
$capsule->bootEloquent();
