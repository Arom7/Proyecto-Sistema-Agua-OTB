<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medidor extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'medidores';
    protected $primaryKey = 'propiedad_id_medidor';

    protected $fillable= [
        'propiedad_id_medidor',
        'id_medidor',
        'medidor_nuevo',
        'medida_inicial',
        'ultima_medida'
    ];

    // Relacion medidores <-- propiedades (Uno a muchos)
    public function propiedad (){
        return $this->belongsTo(Propiedad:: class, 'propiedad_id_medidor', 'id');
    }

    // Relacion medidores --> consumos (Uno a muchos)
    public function consumos(){
        return $this->hasMany(Consumo::class, 'propiedad_id_consumo' ,'propiedad_id_medidor');
    }

    //Mutador para actualizar medida

    public function actualizarUltimaMedida($medida ,$id_propiedad){
        Medidor::where('propiedad_id_medidor', $id_propiedad)->update([
                'ultima_medida' => $medida
        ]);
    }

    public static function busquedaMedidor($propiedad_id){
        return static ::where('propiedad_id_medidor' , $propiedad_id)
                      ->first();
    }

    public static function busquedaMedidorPorId($id_medidor){
        return static ::where('id_medidor' , $id_medidor)
                      ->with('propiedad', 'propiedad.socio')
                      ->first();
    }
}
