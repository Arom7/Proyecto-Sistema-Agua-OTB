<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    use HasFactory;

    protected $table = 'propiedades';
    protected $primaryKey = 'id';

    protected $fillable = [
        'socio_id',
        'cuadra_propiedad',
        'total_multas_propiedad',
        'descripcion_propiedad'
    ];

    // Relacion propiedad -- socio (Una propiedad pertenece a un socio)
    public function socio(){
        return $this->belongsTo(Socio::class,'socio_id','id');
    }

    // Relacion propiedad <-> multas (Una propiedad puede tener muchas multas / Relacion muchos a muchos)
    public function multas(){
        return $this->belongsToMany(Multa::class , 'propiedades_multas' , 'propiedad_id' , 'infracion_id')
                    ->withPivot('fecha_multa','estado_pago')
                    ->withTimestamps();
    }

    // Relacion propiedad -> medidores (Una propiedad puede tener muchos medidores)
    public function medidores() {
        return $this->hasMany(Medidor::class,'propiedad_id_medidor','id');
    }

    //implentacion por cuadra, casa de cuadra a digamos
    public static function buscar_id_propiedad($id_socio, $cuadra){
        return static::where('socio_id', $id_socio)
                      ->where('id', 'like' ,$cuadra.'%')
                      ->get();
    }

    public static function buscar_id_propiedad_unica($id_socio){
        return static::where('socio_id', $id_socio)
                      ->get();
    }
}
