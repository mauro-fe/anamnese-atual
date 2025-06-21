<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Resposta;

class Pergunta extends Model
{
    protected $table = 'perguntas';

    protected $fillable = [
        'admin_id',
        'pergunta',
        'tipo',
        'chave', // << aqui
        'fixa',
        'obrigatorio',
        'opcoes',
        'complemento_texto',
        'placeholder'
    ];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class, 'pergunta_id');
    }
}
