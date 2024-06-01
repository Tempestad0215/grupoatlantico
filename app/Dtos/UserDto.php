<?php

namespace App\Dtos;

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

}
