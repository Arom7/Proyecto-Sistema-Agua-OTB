<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropiedadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'direccion' => $this->direccion_propiedad,
            'deudas' => $this->total_multas_propiedad,
            'descripcion' => $this->descripcion_propiedad,
            'nombre_completo'=> $this->socio ?
                                $this->socio->nombre_socio . ' '
                                .$this->socio->primer_apellido_socio . ' '
                                .$this->socio->segundo_apellido_socio
                                : 'No tiene socio asignado'
        ];
    }
    /**
     * Customize the response for a resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->setData([
            'data' => $this->toArray($request),
            'message' => 'Socio encontrado con exito.',
        ]);
    }
}
