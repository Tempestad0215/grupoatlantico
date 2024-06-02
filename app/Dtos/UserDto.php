<?php

namespace App\Dtos;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgetPasswordUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\PutUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Mail\UserForgetPassword;
use App\Mail\UserRegistered;
use App\Models\ChangePassword;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
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
                Mail::to($user->email)->send(new UserRegistered($user));
                // Mail::to($user->email)->queue(new UserRegistered($user));

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

            if(!$user->email_verified_at)
            {
                Mail::to($user->email)->send(new UserRegistered($user));
            }

            // Crear el token de todo
            $token = $user->createToken($request->email,$user->access)->plainTextToken;

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


    // Olvidod la password
    public function forgetPassword(ForgetPasswordUserRequest $request)
    {
        try {

            // conseguir el usuarios por el email
            $user = User::where("email", $request->email)->first();

            $user->code = generateCode();
            $user->save();

            // Enviar el email con el code registrado
            Mail::to($user->email)->send(new UserForgetPassword($user));

            // Generar el nuevo codigo
            $code = generateCode();

            // Actualizar los datos
            $user->code = $code;
            $user->save();

            // Crear un token temporal para cambiar la password
            $token = $user->createToken($user->email."-temp",["change-password"], now()->addHour() )->plainTextToken;

            // Devover la respuesta
            return successHttp(config("msj.forget"),200, [
                "token" => $token
            ]);

        } catch (\Throwable $th) {
            //throw $th;
            // crear la respuesta
            $response = errorHttp(config('msj.error'),400,$th->getMessage());
            // Devolver el error
            throw new HttpResponseException($response);
        }
    }


    // Cambiar la password
    public function changePassword(ChangePasswordRequest $request)
    {
        try {

            // Sacar el id del usuario autenticado
            $id = Auth::id();

            return DB::transaction(function () use ($id, $request) {

                // Buscar el usuario
                $user = User::where('id', $id)->first();

                // Crear la nueva password
                $user->password = Hash::make($request->password);
                // Guardar los datos
                $user->save();

                // Guardar los datos en la base de datos de cambio de password
                ChangePassword::create([
                    'user_id' => $id
                ]);

                // Elimar el token dado para el cambio de password
                $request->user()->currentAccessToken()->delete();

                // Devolver los datos
                return successHttp(config("msj.passwordChange"),200);
            });


        } catch (\Throwable $th) {
            //throw $th;
            // Respuesta
            $response = errorHttp(config("msj.error"),400, $th->getMessage());

            // Devovolver los datos
            throw new HttpResponseException($response);
        }
    }

}
