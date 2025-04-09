<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moto extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'client_id',
        'image',
    ];

    // Relation avec le modèle de moto
    public function model()
    {
        return $this->belongsTo(MotoModel::class, 'model_id');
    }

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation avec les pièces (schemas)
    public function schemas()
    {
        return $this->hasMany(Schema::class);
    }
}