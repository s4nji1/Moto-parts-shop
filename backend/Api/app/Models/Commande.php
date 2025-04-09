<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'schema_id',
        'quantite',
        'total',     // Ajout du montant total
        'client_id',
        'status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relation avec la pièce de rechange
    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }

    // Méthode pour calculer automatiquement le total
    public function calculateTotal()
    {
        if ($this->schema && $this->quantite) {
            $this->total = $this->schema->price * $this->quantite;
        }
        return $this->total;
    }
}