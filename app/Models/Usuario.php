<?php

namespace App\Models;

use App\Traits\Models\TemPesquisa;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use TemPesquisa;

    protected   $table              = 'Usuarios';
    protected   $primaryKey         = 'UsuarioID';
    public      $incrementing       = true;
    protected   $dateFormat         = 'Y-m-d H:i:s';
    public      $timestamps         = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'Nome',
        'Usuario',
        'Email',
        'Senha',
        'Ativo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'Senha',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Senha' => 'hashed',
            'Ativo' => 'boolean',
        ];
    }

    public function getAuthPassword()
    {
        return $this->Senha;
    }
}
