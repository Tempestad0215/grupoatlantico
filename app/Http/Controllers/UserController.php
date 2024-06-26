<?php

namespace App\Http\Controllers;

use App\Dtos\UserDto;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CheckCode;
use App\Http\Requests\ForgetPasswordUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\PutUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

use function App\Global\errorHttp;
use function App\Global\generateCode;
use function App\Global\successHttp;

class UserController extends Controller implements HasMiddleware
{

    // Publicaciones
    public $userDto;

    // Middleware de autenticacion
    public static function middleware()
    {
        return [
            new Middleware("auth:sanctum", except: ["auth","login","forgetPassword","changePassword"]),
            new Middleware("verified", except: ["login","verifyEmail","forgetPassword"]),
            new Middleware(["auth:sanctum","ability:change-password"], only:["changePassword"]),
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

    public function show(Request $request)
    {
        // Devolver la respuesta
        return $this->userDto->show($request);
    }

    /**
     * Display the specified resource.
     */
    public function edit(User $user)
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

    // Verificar el codigo del usuarios
    public function verifyEmail(CheckCode $request)
    {
        try {

            // Obtener los datos del usuario
            $user = Auth::user();

            // Verificar el codigo ya guardado por el que llega
            if($user->code === $request->code)
            {
                // Volver a generar el codigo
                $code = generateCode();

                // Actualizar los datos del usuarios
                User::where("id", $user->id)->update([
                    "code" => $code,
                    "email_verified_at" => now()
                ]);

                // Devolver el mensaje de exito
                return successHttp(config("msj.verify"),200);

            }

            return errorHttp(config("msj.error"),400,[
                "error" => "El código de verificación no coincide"
            ]);



        } catch (\Throwable $th) {
            //throw $th;
            // respuesta
            $response = errorHttp(config('msj.error'),400, $th->getMessage());

            // Devolver los datos
            throw new HttpResponseException($response);
        }
    }


    // Olvido de password de los usuarios
    public function forgetPassword(ForgetPasswordUserRequest $request)
    {
        // recuperar la data
        $data = $this->userDto->forgetPassword($request);

        // Devolver los datos
        return $data;
    }

    // Cambiar la password
    public function checkCode(CheckCode $request)
    {
        try {

            // Tomar el usuario por el codigo
            $user = User::where("code", $request->code)->first();


            // Verificar que el codigo coincida
            if($user)
            {
                return successHttp(config("msj.code"),200);
            }

            return errorHttp(config("El codigo no coincide"),400);

        } catch (\Throwable $th) {
            //throw $th;

            // Crear la respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }

    // Cambiar la ppassword de todo
    public function changePassword(ChangePasswordRequest $request)
    {
        // Llamr los datos

        // Conseguiir el usaurio
        $data = $this->userDto->changePassword($request);
        // Devover los datos
        return $data;
    }

    // Cerrar la session del usuario
    public function logOut(Request $request)
    {
        try {

            // Eliminar el token del usuario autenticado
            $request->user()->currentAccessToken()->delete();

            // Enviar la respuesta
            return successHttp(config("msj.log-out"),200);


        } catch (\Throwable $th) {
            //throw $th;

            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Enviar la respuesta
            throw new HttpResponseException($response);
        }
    }

}
