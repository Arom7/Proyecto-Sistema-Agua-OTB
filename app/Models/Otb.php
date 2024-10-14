<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otb extends Model
{
    use HasFactory;

    protected $table = 'otbs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre_comunidad_otb',
        'direccion',
    ];

    // Relacion Otb -> socios (una OTB tiene muchos socios)
    public function socios() {
        //Otb esta relacionado uno a muchos con socios, llave foranea de Socio, llave primaria de Otb
        return $this->hasMany(Socio::class,'otb_id','id');
    }

    public function mantenimientos() {
        // Una OTb tiene muchos mantenimientos de equipos
        return $this->hasMany(Mantenimiento::class,'otb_id','id');
    }
}
