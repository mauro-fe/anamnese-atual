<?php

// Renomeie o arquivo para PerguntaModelo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerguntaModelo extends Model
{
    protected $table = 'perguntas_modelo';

    protected $fillable = [
        'pergunta',
        'tipo',
        'opcoes',
        'complemento_texto',
        'placeholder'
    ];

    public $timestamps = false;
}