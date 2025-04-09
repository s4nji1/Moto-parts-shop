<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'schema_id', 'quantity'];

    protected $appends = ['name', 'price', 'imageUrl'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function schema()
    {
        return $this->belongsTo(Schema::class);
    }

    // Ces accesseurs permettent de formater les données pour le client Flutter
    public function getNameAttribute()
    {
        return $this->schema ? $this->schema->nom : '';
    }

    public function getPriceAttribute()
    {
        return $this->schema ? $this->schema->price : 0;
    }

    public function getImageUrlAttribute()
    {
        // Adaptez selon votre structure, si les schémas ont des images
        return $this->schema && $this->schema->moto ? $this->schema->moto->image : '';
    }
}