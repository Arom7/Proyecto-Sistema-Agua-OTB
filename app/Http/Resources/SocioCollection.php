<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SocioCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($socio) {
                return [
                    'id' => $socio->id,
                    'nombre' => $socio->nombre_socio,
                    'apellidos' => $socio->primer_apellido_socio. ' ' . $socio->segundo_apellido_socio,
                    'ci' => $socio->ci_socio,
                    'image-socio' => $socio->image
                ];
            }),
            'message' => 'Lista de socios recuperada con exito.'
        ];
    }
}
