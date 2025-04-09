<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'cin',
        'email',
        'password',
        'phone',
        'address',
    ];

    /**
     * Les attributs qui doivent être cachés.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relation avec les commandes du client
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    /**
     * Relation avec les motos du client
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function motos()
    {
        return $this->hasMany(Moto::class);
    }

    /**
     * Récupère le nom complet du client
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }
}