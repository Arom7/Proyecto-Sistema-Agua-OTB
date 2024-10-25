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
    protected $primaryKey = 'id_consumo';

    protected $fillable = [
        'lectura_actual',
        'mes_correspondiente',
        'propiedad_id_consumo',
        'consumo_total'
    ];

    // Relacion Consumo -- Medidor (Un consumo corresponde a un medidor)
    public function  medidor()
    {
        return $this->belongsTo(Medidor::class, 'propiedad_id_consumo', 'propiedad_id_medidor');
    }

    public function recibos()
    {
        return $this->hasOne(Recibo::class, 'id_consumo_recibo', 'id_consumo');
    }

    public static function buscarConsumo($id_consumo_recibo)
    {
        return static::where('id_consumo', $id_consumo_recibo)
            ->first();
    }

    public static function busquedaConsumoPropiedad($propiedad_id_consumo)
    {
        return static::where('propiedad_id_consumo', $propiedad_id_consumo)
            ->whereHas('recibos', function ($query) {
                $query->where('estado_pago', false);
            })
            ->with('recibos')
            ->get();
    }

    public static function busquedaConsumoPropiedadReciente($propiedad_id_consumo)
    {
        return static::where('propiedad_id_consumo', $propiedad_id_consumo)
            ->whereHas('recibos', function ($query) {
                $query->where('estado_pago', false);
            })
            ->orderBy('id_consumo', 'desc')
            ->with('recibos')
            ->first();
    }

    public static function busquedaConsumoPorRecibo($id_consumo){
        return static::where('id_consumo', $id_consumo)
            ->select('mes_correspondiente','consumo_total')
            ->first();
    }

    // Funcion validacion de datos consumo
    public static function validar($data)
    {
        $reglas = [
            'lectura_actual' => ['required', 'integer']
        ];

        $messages = [
            'lectura_actual.integer' => 'El campo debe ser un numero entero'
        ];

        $validacion = Validator::make($data, $reglas, $messages);

        if ($validacion->fails()) {
            throw new ValidationException($validacion);
        }
        return true;
    }
}
