<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Socio;
use App\Services\SocioService;
use Illuminate\Http\Request;

class SociosController extends Controller
{

    protected $socioService;

    public function __construct(SocioService $socioService)
    {
        $this->socioService = $socioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->socioService->getAllSocios();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
