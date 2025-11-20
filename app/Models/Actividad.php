<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'nota_id',
        'descripcion',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}
