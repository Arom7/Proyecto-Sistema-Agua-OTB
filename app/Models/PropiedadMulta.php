<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropiedadMulta extends Model
{
    use HasFactory;

    protected $table = 'propiedades_multas';

    protected $fillable = [
        'fecha_multa',
        'estado_pago',
        'mes_multa',
        'propiedad_id',
        'infraccion_id'
    ];

    //Relacion PropiedadMulta <- Propiedad
    public function propiedad(){
        return $this->belongsTo(Propiedad::class,'propiedad_id','id');
    }

    //Relacion PropiedadMulta <- Multa
    public function multa(){
        return $this->belongsTo(Multa::class,'infraccion_id','id');
    }

    public static function busquedaMes($mes, $id_propiedad){
        return static:: where('mes_multa',$mes)
            ->where('propiedad_id',$id_propiedad)
            ->get();
    }
}
