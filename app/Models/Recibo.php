<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Recibo extends Model
{
    use HasFactory;

    protected $table = 'recibos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'total',
        'fecha_lectura',
        'observaciones',
        'estado_pago',
        'id_consumo_recibo'
    ];

    // Relacion recibo -- consumo (Relacion uno a uno)
    public function consumo(){
        return $this->belongsTo(Consumo::class , 'id_consumo_recibo' , 'id_consumo');
    }

    //Validacion Recibo
    public static function validar($data){
        $reglas = [
            'observaciones' => 'regex:/^[a-zA-Z0-9]+$/',
        ];

        $message = [
            'observaciones.regex' => 'Solo puedes ingresar letras y numeros.'
        ];

        $validacion =  Validator::make($data,$reglas,$message);

        if($validacion->fails()){
            throw new ValidationException($validacion);
        }
        return true;
    }

    public static function calcularTotal($consumo){
        if ($consumo <= 10){
            return 10;
        }elseif($consumo < 21){
            return $consumo + (0.5*($consumo-10));
        }elseif($consumo<31){
            return $consumo + ($consumo-15);
        }elseif($consumo<41){
            return $consumo + (17 + ($consumo-31) * 2);
        }elseif($consumo<51){
            return $consumo + (38 + ($consumo-41) * 3);
        }else{
            return 115 + (($consumo-50) * 10);
        }
    }

    public static function buscarRecibosFecha($consumo, $fecha_inicio, $fecha_fin){
        return static::where('id_consumo_recibo',$consumo->id_consumo)
                        ->whereBetween('fecha_lectura', [$fecha_inicio, $fecha_fin])
                        ->get();
    }
}
