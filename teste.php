<?php

require_once 'vendor/autoload.php';

use App\Lib\Auth;

echo "Classe carregada: " . (class_exists(Auth::class) ? 'SIM' : 'NÃO');
