<?php

//Los modelos son como volver a realizar una base de datos, aca tenemos que establecer relaciones en como esta armado la base de datos, debe estar igual
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Socio extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'socios';

    //podemos definir que datos pueden ser alterados
    protected $fillable = [
        'nombre_socio',
        'primer_apellido_socio',
        'segundo_apellido_socio',
        'ci_socio',
        'otb_id'
    ];

    // Relacion socio -> usuarios (un socio tiene muchos usuarios)
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'socio_id_usuario','id');
    }

    // Relacion socio->propiedad (un socio tiene muchas propiedades)
    public function propiedades(){
        return $this->hasMany(Propiedad::class,'socio_id','id');
    }

    // Relacion socio->telefono (un socio puede tener muchos telefonos)
    public function telefonos() {
        return $this->hasMany(Telefono::class, 'socio_id_telefono','id');
    }

    // Relacion socio -- otb (un socio pertenece a una otb)
    public function otb() {
        return $this->belongsTo(Otb::class,'otb_id','id');
    }

    public static function usuarioExistente($nombre, $primerApellido, $segundoApellido)
    {
        return static::where('nombre_socio', $nombre)
                     ->where('primer_apellido_socio', $primerApellido)
                     ->where('segundo_apellido_socio', $segundoApellido)
                     ->exists();
    }

    public static function buscar_id_usuario($nombre, $primerApellido, $segundoApellido){
        return static::where('nombre_socio', $nombre)
                     ->where('primer_apellido_socio', $primerApellido)
                     ->where('segundo_apellido_socio', $segundoApellido)
                     ->select('id')
                     ->first();
    }

    //Validacion de datos socios registro
    public static function validar($data){
        $reglas = [
            'nombre' => ['required', 'string', 'regex:/^(?! )[a-zA-Z]+( [a-zA-Z]+)*$/', 'max:85'],
            'primer_apellido' => ['required', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
            'segundo_apellido' => ['nullable', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
            'ci' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:40'],
        ];

        $messages = [
            'nombre.regex' => 'Tu nombre solo puede contener letras y espacios.',
            'primer_apellido.regex' => 'Tu primer apellido solo puede contener letras.',
            'segundo_apellido.regex' => 'Tu segundo apellido solo puede contener letras',
            'ci.regex' => 'El CI solo puede contener letras y números.',
        ];


        $validacion = Validator::make($data,$reglas,$messages);

        if($validacion->failed()){
            throw new ValidationException($validacion);
        }

        return true;
    }

    //Validacion de datos socios recibo
    public static function validar_socio_recibo($data){
        $reglas = [
            'nombre' => ['required', 'string', 'regex:/^(?!\s)(?!.*\s$)[a-zA-Z\s]*[a-zA-Z]+[a-zA-Z\s]*$/', 'max:85'],
            'primerApellido' => ['required', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
            'segundoApellido' => ['nullable', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
        ];

        $messages = [
            'nombre.regex' => 'Tu nombre solo puede contener letras y espacios.',
            'primerApellido.regex' => 'Tu primer apellido solo puede contener letras.',
            'segundoApellido.regex' => 'Tu segundo apellido solo puede contener letras',
        ];

        $validacion = Validator::make($data,$reglas,$messages);
        if($validacion->fails()){
            throw new ValidationException($validacion);
        }
        return true;
    }
}
