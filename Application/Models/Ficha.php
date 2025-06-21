<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    // REMOVIDO: use SoftDeletes; // <- Comentado pois tabela não tem deleted_at

    protected $table = 'fichas';

    protected $fillable = [
        'admin_id',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public $timestamps = true;

    /**
     * Boot do model
     */
    protected static function boot()
    {
        parent::boot();

        // Define valores padrão
        static::creating(function ($ficha) {
            $ficha->status = $ficha->status ?? 'ativa';
        });
    }

    /**
     * Relacionamentos
     */
    public function respostas()
    {
        return $this->hasMany(Resposta::class, 'ficha_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Scopes
     */
    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa');
    }

    public function scopeDoAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeRecentes($query, $dias = 30)
    {
        $data = date('Y-m-d H:i:s', strtotime("-{$dias} days"));
        return $query->where('created_at', '>=', $data);
    }

    /**
     * Simulação de "soft delete" usando status
     */
    public function delete()
    {
        $this->status = 'excluida';
        return $this->save();
    }

    /**
     * "Restaurar" ficha
     */
    public function restore()
    {
        $this->status = 'ativa';
        return $this->save();
    }

    /**
     * Scope para fichas não excluídas
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('status', '!=', 'excluida');
    }

    /**
     * Acessors para dados básicos
     */
    public function getNomeCompletoAttribute(): ?string
    {
        return $this->getRespostaPorChave('nome_completo');
    }

    public function getTelefoneAttribute(): ?string
    {
        return $this->getRespostaPorChave('telefone');
    }

    public function getEmailAttribute(): ?string
    {
        return $this->getRespostaPorChave('email');
    }

    public function getIdadeAttribute(): ?string
    {
        return $this->getRespostaPorChave('idade');
    }

    public function getSexoAttribute(): ?string
    {
        return $this->getRespostaPorChave('sexo');
    }

    /**
     * Pega resposta por chave da pergunta
     */
    public function getRespostaPorChave(string $chave): ?string
    {
        $resposta = $this->respostas()
            ->whereHas('pergunta', function ($q) use ($chave) {
                $q->where('chave', $chave);
            })
            ->first();

        return $resposta ? $resposta->resposta : null;
    }

    /**
     * Pega todas as respostas formatadas
     */
    public function getRespostasFormatadas(): array
    {
        return $this->respostas()
            ->with('pergunta')
            ->get()
            ->mapWithKeys(function ($resposta) {
                return [
                    $resposta->pergunta->chave => [
                        'pergunta' => $resposta->pergunta->pergunta,
                        'resposta' => $resposta->resposta,
                        'tipo' => $resposta->pergunta->tipo
                    ]
                ];
            })
            ->toArray();
    }

    /**
     * Verifica se a ficha está completa
     */
    public function estaCompleta(): bool
    {
        $perguntasObrigatorias = Pergunta::where(function ($q) {
            $q->where('admin_id', $this->admin_id)
                ->orWhere(function ($sq) {
                    $sq->whereNull('admin_id')->where('fixa', 1);
                });
        })
            ->where('obrigatorio', 1)
            ->pluck('id');

        $respostasPreenchidas = $this->respostas()
            ->whereIn('pergunta_id', $perguntasObrigatorias)
            ->whereNotNull('resposta')
            ->where('resposta', '!=', '')
            ->pluck('pergunta_id');

        return $perguntasObrigatorias->diff($respostasPreenchidas)->isEmpty();
    }

    /**
     * Calcula porcentagem de preenchimento
     */
    public function getPorcentagemPreenchimento(): int
    {
        $totalPerguntas = Pergunta::where(function ($q) {
            $q->where('admin_id', $this->admin_id)
                ->orWhere(function ($sq) {
                    $sq->whereNull('admin_id')->where('fixa', 1);
                });
        })
            ->count();

        if ($totalPerguntas === 0) {
            return 0;
        }

        $respostasPreenchidas = $this->respostas()
            ->whereNotNull('resposta')
            ->where('resposta', '!=', '')
            ->count();

        return (int) (($respostasPreenchidas / $totalPerguntas) * 100);
    }

    /**
     * Gera identificador único para URLs
     */
    public function gerarIdentificadorUnico(): string
    {
        $nome = $this->nome_completo ?? 'paciente';
        $slug = \App\Lib\Helpers::slugify($nome);

        return $slug . '-' . $this->id;
    }
}
