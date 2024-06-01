<?php

namespace App\Dtos;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\PutUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function App\Global\errorHttp;
use function App\Global\generateCode;
use function App\Global\successHttp;

Class UserDto {

    public function store(StoreUserRequest $request)
    {
        try {

            // Proteger la creadion del usuario
            return  DB::transaction(function () use ($request) {
                // Genera codigo
                $code = generateCode();

                // Guardar los datos
                $user = User::create([
                    'name' => Str::upper($request->name),
                    'last_name' => Str::upper($request->last_name),
                    'email' => Str::lower($request->email),
                    'password' => Hash::make($request->password),
                    'code' => $code,
                    'access' => $request->access,
                ]);

                // Enviar el email para el usuario registado
                Mail::to($user->email)->queue(new UserRegistered($user));

                // Devolver los datos del usuarios
                return $user;

            });


        } catch (\Throwable $th) {

            $response = errorHttp("Error en esta peticion",400, $th->getMessage());
            // DEvolver los datos
            throw new HttpResponseException($response);
        }


    }

    // Actualizar los datos
    public function update(PutUserRequest $request, User $user)
    {
        try {

            // Actualizar los datos
            $user->update([
                "name" => $request->name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "access" => $request->access,
            ]);

            // Devovver los datos
            return $user;


        } catch (\Throwable $th) {
            // Crear la respuesta
            $response = errorHttp("Error en esta peticion",400, $th->getMessage());
            // DEvolver los datos
            throw new HttpResponseException($response);
        }
    }


    // Login de usuarios
    public function login(LoginUserRequest $request)
    {
        try {
            // Tratar de econtrar el email
            $user = User::where("email", $request->email)->first();


            // Verificar que el usuario exista correctamente
            if(!$user)
            {
                // Crear la respuesta
                $response = errorHttp("Usuario no existe o mal escrito",400);

                // Enviar la respuesta
                return $response;
            }

            // Verificar la password
            if(!Hash::check($request->password, $user->password))
            {
                // Crear la respuesta
                $response = errorHttp("Contraseña incorrecta",400);

                // Enviar la respuesta
                return $response;
            }

            // Crear el token de todo
            $token = $user->createToken("TOKEN-ACCESS",$user->access)->plainTextToken;

            // devolver el token correctamente
            $data = [
                "token" => $token
            ];

            // Crear la respuesta
            $response = successHttp(config('msj.login'),200, $data);

            // Devolver la respuesta
            return $response;


        } catch (\Throwable $th) {
            //throw $th;
            // Crear la respuesta
            $response = errorHttp(config('msj.error'),400, $th->getMessage());

            // Devolver la respuesta
            throw new HttpResponseException($response);
        }
    }

}
