<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'nombre' => $this->nombre_socio,
            'apellidos' => $this->primer_apellido_socio. ' ' . $this->segundo_apellido_socio,
            'ci' => $this->ci_socio,
            'image-socio' => $this->image
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
