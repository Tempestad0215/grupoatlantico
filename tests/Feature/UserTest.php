<?php

namespace Tests\Feature;

use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_create(): void
    {
        // sin ecepciones
        $this->withoutExceptionHandling();
        // crear un usuario
        $user = User::factory()->create();

        Mail::fake();

        // HAcer la peticion de todo
        $this->actingAs($user)->post("/apiv1/user",[
            "name" => "marionil julio",
            "last_name" => "guzman gonzalez",
            "email"=> "marioguzman140@gmail.com",
            "password" => "Password02+",
            "password_confirmation" => "Password02+",
            "access" => ["read","update","delete","edit"]
        ])->assertStatus(200)
        ->assertJson([
            "status" => "success",
            "message"=> "Usuario registrado correctamente",
            "data" => []
        ]);

        // Verificar si el envia
        Mail::assertQueued(UserRegistered::class);

    }


    // Email con algun error
    public function test_user_create_fail(): void
    {
        // sin ecepciones
        $this->withoutExceptionHandling();
        // crear un usuario
        $user = User::factory()->create();

        // HAcer la peticion de todo
        $this->actingAs($user)->post("/apiv1/user",[
            "name" => "marionil julio",
            "last_name" => "guzman gonzalez",
            "email"=> "marioguzman140@gmail.com",
            "password" => "Password02",
            "password_confirmation" => "Password02+",
            "access" => ["read","update","delete","edit"]
        ])->assertStatus(422)
        ->assertJson([
            "status" => "error",
            "message"=> "La validación contiene errores",
            "errors" => []
        ]);

    }


    // Conseguir un usuiarios
    public function test_user_edit():void
    {
        // Sin excepcion
        $this->withoutExceptionHandling();
        // Crear los usuarios
        $users = User::factory(35)->create();

        $this->actingAs($users[2])
            ->get("/apiv1/user/2")
            ->assertStatus(200)
            ->assertJson([
                "status" => "success",
                "message" => "Datos obtenido correctamente",
                "code" => 200
            ]);

    }

    // Actualizar los datos
    public function test_user_update(): void
    {
        // Si exceptcion
        $this->withoutExceptionHandling();

        // crear un usuario
        $user = User::factory()->create([
            'name' => 'pablo julio',

            'email' => 'example@examplo.com'
        ]);

        // Craer otro usuario
        $user1 = User::factory()->create();

        // HAcer la peticionm
        $this->actingAs($user1)->put("/apiv1/user/1",[
            'name' => 'marionil julio',
            'last_name' => $user->last_name,
            'email' => 'marioguzman140@gmail.com',
            'access' => $user->access
        ])->assertStatus(200)
            ->assertJson([
                "status" => "success",
                "code" => 200,
                "message"=> "Usuario actualizado correctamente",
            ]);


    }


}
