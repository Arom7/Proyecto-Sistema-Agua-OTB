<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
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

    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function socio(){
        return $this->belongsTo(Socio::class,'socio_id_usuario','id');
    }

    public static function cuentaExistente($username)
    {
        return static::where('username', $username)
                     ->exists();
    }

    public static function generarUsername ($data){
        $username = strtolower(substr($data->nombre_socio,0,2).substr($data->primer_apellido_socio,0,2).substr($data->segundo_apellido_socio,0,2).rand(1000,9999));
        if(!Usuario::cuentaExistente($username)){
            return $username;
        }else{
            return Usuario::generarUsername($data);
        }
    }

    public static function validar($data){
        $rules = [
            'email' => ['required', 'email', 'unique:usuarios,email'],
            'contrasenia' => ['required', 'string' , 'regex:/^[\w\s!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?~`]+$/' , 'min:8']
        ];

        $message = [
            'email.email' => 'El campo debe ser una direccion electronica valida',
            'email.unique' => 'El correo ya se encuentra registrado.',
            'contrasenia.regex' => ' Combinar entre mayusculas, minusculas, numeros y caracteres especiales.',
            'contrasenia.min' => 'La contrasenia debe contener al menos 8 caracteres.'
        ];

        $validacion = Validator::make($data, $rules , $message);

        if ($validacion -> fails()){
            throw new ValidationException($validacion);
        }

        return true;
    }

    public static function validarIngreso($data){
        $rules = [
            'username' => ['required', 'string'],
            'contrasenia' => ['required', 'string']
        ];

        $message = [
            'username.required' => 'El campo usuario es requerido',
            'contrasenia.required' => 'El campo contrasenia es requerido'
        ];

        $validacion = Validator::make($data, $rules, $message);

        if ($validacion -> fails()){
            throw new ValidationException($validacion);
        }

        return true;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->email));
    }
}
