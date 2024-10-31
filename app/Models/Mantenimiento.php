<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Mantenimiento extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'mantenimientos';

    protected $fillable = [
        'fecha_mantenimiento_inicio',
        'fecha_mantenimiento_fin',
        'descripcion_mantenimiento',
        'responsable',
        'precio_total',
        'tipo_equipo',
        'fecha_proximo_mantenimiento',
        'otb_id'
    ];

    public function otb() {
        return $this->belongsTo(Otb::class,'otb_id','id');
    }

    public static function validar($data){
        $rules = [
            'fecha_mantenimiento_inicio' => ['required' , 'date'],
            'fecha_mantenimiento_fin' => ['required','date'],
            'descripcion_mantenimiento' => ['string'],
            'responsable' => ['required', 'string'],
            'precio_total' => ['required','numeric'],
            'tipo_equipo' => ['required', 'string'],
            'fecha_proximo_mantenimiento' => ['required','date'],
        ];

        $messages = [
            'fecha_mantenimiento_inicio.date' => 'La fecha de inicio del mantenimiento debe ser una fecha',
            'fecha_mantenimiento_fin.date' => 'La fecha de fin del mantenimiento debe ser una fecha',
            'descripcion_mantenimiento.string' => 'La descripcion del mantenimiento debe ser un texto',
            'responsable.string' => 'El responsable del mantenimiento debe ser un texto',
            'precio_total.numeric' => 'El precio total del mantenimiento debe ser un numero',
            'tipo_equipo.string' => 'El tipo de equipo debe ser un texto',
            'fecha_proximo_mantenimiento.date' => 'La fecha del proximo mantenimiento debe ser una fecha',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
