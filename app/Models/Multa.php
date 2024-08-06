<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    use HasFactory;

    protected $table = 'multas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'criterio_infraccion',
        'descripcion_infraccion',
        'monto_infraccion'
    ];

    //Relacion propiedad <-> multas (Una multa puede pertenecer a muchas propiedades / Relacion muchos a muchos)
    public function propiedades(){
        return $this->belongsToMany(Propiedad::class,'propiedades_multas','infracion_id','propiedad_id')
                    ->withPivot('fecha_multa','estado_pago')
                    ->withTimestamps();
    }


    //Mutadores y accesores
}
