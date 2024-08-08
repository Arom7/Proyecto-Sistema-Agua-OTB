<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'usuarios';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'contrasenia',
        'socio_id_usuario',

    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contrasenia',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'contrasenia' => 'hashed',
    ];

    public function socio(){
        return $this->belongsTo(Socio::class,'socio_id_usuario','id');
    }

    public static function cuentaExistente($username)
    {
        return static::where('username', $username)
                     ->exists();
    }

    public static function validar($data){
        $rules = [
            'username' => ['required', 'string' , 'regex:/^[a-zA-Z0-9]+$/', 'min: 6', 'max:15'],
            'email' => ['required', 'email', 'unique:usuarios,email'],
            'contrasenia' => ['required', 'string' , 'regex:/^[\w\s!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?~`]+$/' , 'min:8']
        ];

        $message = [
            'username.regex' => 'Su username debe contener solo letras mayusculas o minusculas ademas de numeros.',
            'email.email' => 'El campo debe ser una direccion electronica valida',
            'email.unique' => 'El correo ya se encuentra registrado.',
            'contrasenia.regex' => ' Combinar entre mayusculas, minusculas, numeros y caracteres especiales.',
            'contrasenia.min' => 'La contrasenia debe contener al menos 8 caracteres.'
        ];

        $validacion = Validator::make($data,$rules , $message);

        if ($validacion -> fails()){
            throw new ValidationException($validacion);
        }

        return true;
    }
}
