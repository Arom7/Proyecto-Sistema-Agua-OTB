<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Consumo extends Model
{
    use HasFactory;

    protected $table = 'consumos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lectura_actual',
        'mes_correspondiente',
        'propiedad_id_consumo',
        'consumo_total'
    ];

    // Relacion Consumo -- Medidor (Un consumo corresponde a un medidor)
    public function  medidor() {
        return $this->belongsTo(Medidor::class , 'propiedad_id_consumo', 'propiedad_id_medidor');
    }

    public function recibos(){
        return $this-> hasOne(Recibo::class, 'id_consumo_recibo' , 'propiedad_id_consumo');
    }

    // Funcion validacion de datos consumo
    public static function validar($data){
        $reglas = [
            'mes_correspondiente' => ['required', 'date'],
            'lectura_actual'=> ['required', 'integer'],
        ];

        $messages = [
            'lectura_actual.integer' => 'El campo debe ser un numero entero',
            'mes_correspondiente.date' => 'El campo debe ser un una fecha'
        ];

        $validacion = Validator::make($data,$reglas,$messages);

        if($validacion->fails()){
            throw new ValidationException($validacion);
        }

        return true;
    }
 }
