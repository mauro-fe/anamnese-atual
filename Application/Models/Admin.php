<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use SoftDeletes;

    protected $table = 'admins';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'username',
        'password',
        'nome_completo',
        'email',
        'nome_clinica',
        'razao_social',
        'cnpj',
        'telefone',
        'ativo',
        'ultimo_login',
        'password_changed_at'
    ];

    // Campos ocultos ao serializar para array/json
    protected $hidden = [
        'password',
        'old_password_1',
        'old_password_2',
        'remember_token',
        'deleted_at'
    ];

    // Casts de tipos
    protected $casts = [
        'ativo' => 'boolean',
        'ultimo_login' => 'datetime',
        'password_changed_at' => 'datetime',
        'email_verified_at' => 'datetime'
    ];

    public $timestamps = true;

    /**
     * Boot do model
     */
    protected static function boot()
    {
        parent::boot();

        // Define valores padrão ao criar
        static::creating(function ($admin) {
            $admin->ativo = $admin->ativo ?? true;
        });
    }

    /**
     * Escopo para admins ativos
     */
    public function scopeActive($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Escopo para login por email ou username
     */
    public function scopeByEmailOrUsername($query, $login)
    {
        $login = trim(strtolower($login));
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return $query->whereRaw("LOWER($field) = ?", [$login]);
    }

    /**
     * Autenticação segura
     */
    public static function authenticate($login, $password)
    {
        $admin = self::active()
            ->byEmailOrUsername($login)
            ->first();

        if ($admin && password_verify($password, $admin->password)) {
            // Atualiza último login
            $admin->ultimo_login = date('Y-m-d H:i:s');
            $admin->save();

            return $admin;
        }

        return null;
    }

    /**
     * Mutator para password - hash automático apenas se não for hash
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value) && !$this->isPasswordHashed($value)) {
            $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Mutator para old_password_1
     */
    public function setOldPassword1Attribute($value)
    {
        if (!empty($value) && !$this->isPasswordHashed($value)) {
            $this->attributes['old_password_1'] = password_hash($value, PASSWORD_DEFAULT);
        } else {
            $this->attributes['old_password_1'] = $value;
        }
    }

    /**
     * Mutator para old_password_2
     */
    public function setOldPassword2Attribute($value)
    {
        if (!empty($value) && !$this->isPasswordHashed($value)) {
            $this->attributes['old_password_2'] = password_hash($value, PASSWORD_DEFAULT);
        } else {
            $this->attributes['old_password_2'] = $value;
        }
    }

    /**
     * Verifica se string já é um hash de senha
     */
    private function isPasswordHashed($value): bool
    {
        return is_string($value) && password_get_info($value)['algo'] !== 0;
    }

    /**
     * Verifica se a senha precisa ser trocada (mais de 90 dias)
     */
    public function needsPasswordChange(): bool
    {
        if (!$this->password_changed_at) {
            return true;
        }

        $passwordDate = new \DateTime($this->password_changed_at);
        $now = new \DateTime();
        $interval = $now->diff($passwordDate);

        return $interval->days > 90;
    }

    /**
     * Gera token para lembrar login
     */
    public function generateRememberToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->remember_token = hash('sha256', $token);
        $this->save();

        return $token;
    }

    /**
     * Valida token de lembrar
     */
    public static function findByRememberToken($token): ?self
    {
        $hashedToken = hash('sha256', $token);
        return self::where('remember_token', $hashedToken)->first();
    }

    /**
     * Relacionamentos
     */
    public function perguntas()
    {
        return $this->hasMany(Pergunta::class, 'admin_id');
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class, 'admin_id');
    }

    public function fichas()
    {
        return $this->hasMany(Ficha::class, 'admin_id');
    }

    /**
     * Acessors úteis
     */
    public function getSlugClinicaAttribute(): string
    {
        return \App\Lib\Helpers::slugify($this->nome_clinica ?? 'clinica');
    }

    public function getNomeCompletoInicaisAttribute(): string
    {
        $nomes = explode(' ', $this->nome_completo);
        if (count($nomes) > 1) {
            return $nomes[0] . ' ' . $nomes[count($nomes) - 1][0] . '.';
        }
        return $this->nome_completo;
    }
}
