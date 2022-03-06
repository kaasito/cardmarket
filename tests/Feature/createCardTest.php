<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class createCardTest extends TestCase
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
   
    public function test_createCardNotAuthorized()
    {
        $response = $this->postJson('/api/cartas/crear',[
            "Api_token" => '$2y$10$srbVvtjaUa/OxZJaOefUouMRHoNB00qxKUZpSXNNVju1/mcpK5wsy',
            "descripcion" => "Carta",
            "nombre" => "Carta no autorizada",
            "id_coleccion" => 1
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                   "status" => 0
        ]);

    }
    public function test_createCardEmptyData()
    {
        $response = $this->postJson('/api/cartas/crear',[
            "Api_token" => '$2y$10$srbVvtjaUa/OxZJaOefUouMRHoNB00qxKUZpSXNNVju1/mcpK5wsy',
            "descripcion" => "",
            "nombre" => "",
            "id_coleccion" => ""
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                   "status" => 0
        ]);

    }
    public function test_createCardIncorrectCollection()
    {
        $response = $this->postJson('/api/cartas/crear',[
            "Api_token" => '$2y$10$P3Vje5i8srPH5QxVfYp2A.1Q./7/OUaXHhj0SCNK33lm9IBDkVPK6',
            "descripcion" => "Carta",
            "nombre" => "Coleccion no correcta",
            "id_coleccion" => 2
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                   "status" => 0
        ]);

    }

    public function test_createCardCorrectData()
    {
        $response = $this->postJson('/api/cartas/crear',[
            "Api_token" => '$2y$10$P3Vje5i8srPH5QxVfYp2A.1Q./7/OUaXHhj0SCNK33lm9IBDkVPK6',
            "descripcion" => "Carta",
            "nombre" => "Carta no autorizada",
            "id_coleccion" => 1
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                   "status" => 1
        ]);

    }
}
