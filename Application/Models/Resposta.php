<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    protected $table = 'respostas';

    protected $fillable = [
        'ficha_id',
        'pergunta_id',
        'admin_id',
        'resposta'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relacionamentos
     */
    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class, 'pergunta_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function ficha()
    {
        return $this->belongsTo(Ficha::class, 'ficha_id');
    }

    /**
     * Scopes úteis
     */
    public function scopeComPergunta($query)
    {
        return $query->with('pergunta');
    }

    public function scopePorChave($query, $chave)
    {
        return $query->whereHas('pergunta', function ($q) use ($chave) {
            $q->where('chave', $chave);
        });
    }

    /**
     * Acessors
     */
    public function getRespostaFormatadaAttribute(): string
    {
        // Se for checkbox ou select múltiplo, formata melhor
        if (strpos($this->resposta, ',') !== false) {
            $opcoes = explode(',', $this->resposta);
            return implode(', ', array_map('trim', $opcoes));
        }

        return $this->resposta;
    }

    /**
     * Helpers
     */
    public function temComplemento(): bool
    {
        return strpos($this->resposta, ' | Obs:') !== false;
    }

    public function getRespostaPrincipal(): string
    {
        if ($this->temComplemento()) {
            return trim(explode(' | Obs:', $this->resposta)[0]);
        }

        return $this->resposta;
    }

    public function getComplemento(): ?string
    {
        if ($this->temComplemento()) {
            return trim(explode(' | Obs:', $this->resposta)[1]);
        }

        return null;
    }
}
