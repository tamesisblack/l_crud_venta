<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria',
        'fecha_agregado',
        'estado',
    ];
    protected $dates = [
        'fecha_agregado',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';
    // Método para obtener el precio formateado
    public function getPrecioFormateadoAttribute()
    {
        return number_format($this->precio, 2);
    }

    // Método para obtener el estado del producto en texto
    public function getEstadoTextoAttribute()
    {
        return $this->estado ? 'Disponible' : 'No Disponible';
    }
}
