<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'users';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];

    /**
     * Los atributos que deben ocultarse para serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación uno a uno con Cliente.
     * Un usuario puede tener un perfil de cliente (titular de cuenta).
     */
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }

    /**
     * Obtiene las iniciales del usuario.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Verifica si el usuario tiene un cliente asociado.
     */
    public function tieneCliente(): bool
    {
        return $this->cliente()->exists();
    }

    /**
     * Verifica si el email del usuario ha sido verificado.
     */
    public function emailVerificado(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Scope para filtrar usuarios con email verificado.
     */
    public function scopeEmailVerificado($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope para filtrar usuarios sin email verificado.
     */
    public function scopeEmailNoVerificado($query)
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Scope para buscar usuarios por nombre o email.
     */
    public function scopeBuscar($query, string $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('name', 'like', "%{$termino}%")
              ->orWhere('email', 'like', "%{$termino}%");
        });
    }

    /**
     * Scope para ordenar por más recientes.
     */
    public function scopeRecientes($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Obtiene el nombre del primer nombre del usuario.
     */
    public function primerNombre(): string
    {
        return Str::of($this->name)->explode(' ')->first();
    }

    /**
     * Obtiene un saludo personalizado según la hora del día.
     */
    public function saludoPersonalizado(): string
    {
        $hora = now()->hour;
        $primerNombre = $this->primerNombre();

        if ($hora >= 6 && $hora < 12) {
            return "Buenos días, {$primerNombre}";
        } elseif ($hora >= 12 && $hora < 19) {
            return "Buenas tardes, {$primerNombre}";
        } else {
            return "Buenas noches, {$primerNombre}";
        }
    }

    /**
     * Obtiene el avatar con las iniciales del usuario.
     * Útil para generar avatares por defecto.
     */
    public function avatarUrl(int $size = 100): string
    {
        $iniciales = urlencode($this->initials());
        $background = substr(md5($this->email), 0, 6);
        
        return "https://ui-avatars.com/api/?name={$iniciales}&size={$size}&background={$background}&color=fff";
    }

    /**
     * Formatea el nombre completo del usuario.
     */
    public function nombreFormateado(): string
    {
        return Str::title($this->name);
    }

    /**
     * Obtiene información del cliente si existe.
     */
    public function infoCliente(): ?array
    {
        if (!$this->tieneCliente()) {
            return null;
        }

        $cliente = $this->cliente;
        
        return [
            'nombre_titular' => $cliente->nombre_titular,
            'plan' => $cliente->plan,
            'suscripcion_activa' => $cliente->suscripcion_activa,
            'email_contacto' => $cliente->email_contacto,
        ];
    }
} 
