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
        'image',
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

    public static function usuarioExistente($ci_socio)
    {
        return static::where('ci_socio', $ci_socio)
                     ->exists();
    }

    public static function buscar_id_usuario($ci_socio){
        return static::where('ci_socio', $ci_socio)
                     ->select('id')
                     ->first();
    }

    //Validacion de datos socios registro
    public static function validar($data){
        $reglas = [
            'nombre_socio' => ['required', 'string', 'regex:/^(?! )[a-zA-Z]+( [a-zA-Z]+)*$/', 'max:85'],
            'primer_apellido_socio' => ['required', 'string', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/', 'max:85'],
            'segundo_apellido_socio' => ['nullable', 'string', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$', 'max:85'],
            'ci_socio' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:40','unique:socios,ci_socio'],
        ];

        $messages = [
            'nombre_socio.regex' => 'Tu nombre solo puede contener letras y espacios.',
            'primer_apellido_socio.regex' => 'Tu primer apellido solo puede contener letras.',
            'segundo_apellido_socio.regex' => 'Tu segundo apellido solo puede contener letras',
            'ci_socio.regex' => 'El CI solo puede contener letras y números.',
            'ci_socio.unique' => 'El CI ya se encuentra registrado.',
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
            'nombre_socio' => ['required', 'string', 'regex:/^(?!\s)(?!.*\s$)[a-zA-Z\s]*[a-zA-Z]+[a-zA-Z\s]*$/', 'max:85'],
            'primer_apellido_socio' => ['required', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
            'segundo_apellido_socio' => ['nullable', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
        ];

        $messages = [
            'nombre_socio.regex' => 'Tu nombre solo puede contener letras y espacios.',
            'primer_apellido_socio.regex' => 'Tu primer apellido solo puede contener letras.',
            'segundo_apellido_socio.regex' => 'Tu segundo apellido solo puede contener letras',
        ];

        $validacion = Validator::make($data,$reglas,$messages);
        if($validacion->fails()){
            throw new ValidationException($validacion);
        }
        return true;
    }

    //Validar datos del socio
    public static function validar_socio($data){
        $reglas = [
            'nombre_socio' => ['sometimes', 'string', 'regex:/^(?!\s)(?!.\s$)[a-zA-Z\s][a-zA-Z]+[a-zA-Z\s]*$/', 'max:85'],
            'primer_apellido_socio' => ['sometimes', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
            'segundo_apellido_socio' => ['sometimes', 'string', 'regex:/^[a-zA-Z]+$/', 'max:85'],
            'ci_socio' => ['sometimes', 'string', 'regex:/^[a-zA-Z0-9]+$/', 'max:20'],
        ];

        $messages = [
            'nombre_socio.regex' => 'Tu nombre solo puede contener letras y espacios.',
            'primer_apellido_socio.regex' => 'Tu primer apellido solo puede contener letras.',
            'segundo_apellido_socio.regex' => 'Tu segundo apellido solo puede contener letras',
            'ci_socio.regex' => 'El CI solo puede contener letras y números.',
        ];

        $validacion = Validator::make($data,$reglas,$messages);

        if($validacion->fails()){
            throw new ValidationException($validacion);
        }

        return true;
    }
}
