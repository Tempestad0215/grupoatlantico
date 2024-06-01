<?php

namespace App\Http\Controllers;

use App\Dtos\UserDto;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\PutUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use function App\Global\errorHttp;
use function App\Global\successHttp;

class UserController extends Controller implements HasMiddleware
{

    // Publicaciones
    public $userDto;

    // Middleware de autenticacion
    public static function middleware()
    {
        return [
            new Middleware("auth:sanctum", except: ["auth",'login']),
            new Middleware("verified", except: ["login"]),
        ];
    }

    // Constructor
    public function __construct()
    {
        // Llamar el dto
        $this->userDto = new UserDto();
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // guardar los datos
        $data = $this->userDto->store($request);

        // Devolver los datos
        return successHttp("Usuario registrado correctamente", 200, $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {

            return successHttp("Datos obtenido correctamente",200, $user);

        } catch (\Throwable $th) {
            // Devolver los datos
            $response = errorHttp("Error al intentar conseguir el usuarios",400);

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutUserRequest $request, User $user)
    {
        // conseguir los datos
        $data = $this->userDto->update($request, $user);

        // Devolver la respuesta
        return successHttp("Usuario actualizado correctamente",200, $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Actalizar el estado
            $user->update([
                "status"=> true]
            );
            // Devolver los el mensaje de exito
            return successHttp("Usuario eliminado correctamente",200);

        } catch (\Throwable $th) {
            //throw $th;
            // Crear la respuesta
            $response = errorHttp("Error al intentar eliminar el usuario",400, $th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }

    // Crear el login de usuario
    public function login(LoginUserRequest $request )
    {
        // Llamar el login
        $data = $this->userDto->login($request);


        return $data;

    }


}
