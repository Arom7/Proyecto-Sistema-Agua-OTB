<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            return 115 + (($consumo-50)*10);
        }
    }
}
