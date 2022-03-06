<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class loginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_loginUserNotFound()
    {
        $response = $this->postJson('/api/usuarios/login',[
            "nickname" => "lucas@gmail.com",
            "password" => "1234"
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                   "status" => 0
        ]);

    }

    public function test_loginWrongPassword()
    {
        $response = $this->postJson('/api/usuarios/login',[
            "nickname" => "hola",
            "password" => "1234"
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                   "status" => 0
        ]);

    }

    public function test_loginCorrectPassword()
    {
        $response = $this->postJson('/api/usuarios/login',[
            "nickname" => "hola",
            "password" => "Lui$11"
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                   "status" => 1
        ]);

    }
}
