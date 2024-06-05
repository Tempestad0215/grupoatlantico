<?php

namespace App\Http\Controllers;

use App\Dtos\EntranceDto;
use App\Models\Entrance;
use App\Http\Requests\StoreEntranceRequest;
use App\Http\Requests\UpdateEntranceRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EntranceController extends Controller implements HasMiddleware
{

    // Propiedades
    public $entranceDto;

    // Middleware
    public static function middleware()
    {
        return [
            new Middleware("auth:sanctum")
        ];
    }

    // Constructor
    public function __construct()
    {
        $this->entranceDto = new EntranceDto();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntranceRequest $request)
    {

        // Llamar los datos y devolver
        return  $this->entranceDto->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Entrance $entrance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrance $entrance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntranceRequest $request, Entrance $entrance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrance $entrance)
    {
        //
    }
}
