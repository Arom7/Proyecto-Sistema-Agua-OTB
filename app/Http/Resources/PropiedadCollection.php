<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PropiedadCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($propiedad) {
                return [
                    'id' => $propiedad->id,
                    'direccion' => $propiedad->direccion_propiedad,
                    'deudas' => $propiedad->total_multas_propiedad,
                    'descripcion' => $propiedad->descripcion_propiedad,
                    'nombre_completo'=> $propiedad->socio ?
                                        $propiedad->socio->nombre_socio . ' '
                                        .$propiedad->socio->primer_apellido_socio . ' '
                                        .$propiedad->socio->segundo_apellido_socio
                                        : 'No tiene socio asignado'
                ];
            }),
            'message' => 'Lista de propiedades recuperada con exito.'
        ];
    }
}
