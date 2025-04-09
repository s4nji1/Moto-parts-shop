<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotoModel extends Model
{
    use HasFactory;

    // Spécification explicite du nom de la table
    // car 'models' pourrait être confondu avec le namespace d'Eloquent
    protected $table = 'models';

    protected $fillable = [
        'marque',
        'annee',
    ];

    // Relation avec les motos
    public function motos()
    {
        return $this->hasMany(Moto::class, 'model_id');
    }
}