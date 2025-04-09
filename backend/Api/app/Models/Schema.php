<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'parent_id',
        'version',
        'price',
        'moto_id',
        'image',
        'serial_number',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relation avec la pièce parente
    public function parent()
    {
        return $this->belongsTo(Schema::class, 'parent_id');
    }

    // Relation avec les pièces enfants
    public function enfants()
    {
        return $this->hasMany(Schema::class, 'parent_id');
    }

    // Relation avec les commandes
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    // Relation avec la moto
    public function moto()
    {
        return $this->belongsTo(Moto::class);
    }

    public function compatibleModels()
    {
        return $this->belongsToMany(MotoModel::class, 'schema_model_compatibility', 'schema_id', 'model_id');
    }
}