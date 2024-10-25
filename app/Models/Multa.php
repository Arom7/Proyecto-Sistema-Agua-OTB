<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Multa extends Model
{
    use HasFactory;

    protected $table = 'multas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'criterio_infraccion',
        'descripcion_infraccion',
        'estado_activo',
        'monto_infraccion'
    ];

    //Relacion propiedad <-> multas (Una multa puede pertenecer a muchas propiedades / Relacion muchos a muchos)
    public function propiedades()
    {
        return $this->belongsToMany(Propiedad::class, 'propiedades_multas', 'infracion_id', 'propiedad_id')
            ->withPivot('fecha_multa', 'estado_pago')
            ->withTimestamps();
    }


    public static function multasPorPropiedad($id_propiedad, $mes)
    {
        return static::whereHas('propiedades', function ($query) use ($id_propiedad, $mes) {
            $query->where('propiedad_id', $id_propiedad)
                  ->where('mes_multa', $mes)
                  ->where('estado_pago', false);
        })->get();
    }


    public static function validar($data)
    {
        $reglas = [
            'criterio_infraccion' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]+([ ,.\-]*[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]*)*$/'],
            'descripcion_infraccion' => ['required', 'string','regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]+([ ,.\-]*[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9]*)*$/'],
            'monto_infraccion' => ['required', 'numeric'],
        ];

        $message = [
            'monto_infraccion.numeric' => 'Este campo debe ser un valor numerico'
        ];

        $validacion =  Validator::make($data, $reglas, $message);

        if ($validacion->fails()) {
            throw new ValidationException($validacion);
        }
        return true;
    }
    //Mutadores y accesores
}
