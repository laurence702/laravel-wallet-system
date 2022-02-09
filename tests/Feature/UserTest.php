<?php

namespace Tests\Feature;

use Tests\TestCase;
use SebastianBergmann\Environment\Console;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    //GIVEN : the precondition fr test to work
            //--> user is authenticated
     //WHEN : the action to take
            //-->post request create product, user etc
     //THEN : the outcome
            //-->product exists
        
    protected function setUp(): void {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function can_display_users_by_id()
    {
        
        $response = $this->json('GET','/api/v1/users/2');

        $response->assertJson(['status' => true]);
        $response->assertStatus(200);
    }
    /**
     * @test
     */

    public function can_create_user(){
        $new_user = [
            "first_name" => "Ikenna",
            "last_name" => "Igbokwe",
            "phone" => "08131361241",
            "email" => "igbokwelaurence@gmail.com",
            "pin" => "8289",
        ];
        
        $response = $this->json('POST', '/api/v1/users', $new_user);
        $this->assertDatabaseHas('users',[
            'first_name'=>'Ikenna',
            'last_name' => 'Igbokwe',
            'phone' => '08131361241',
            'email' => 'igbokwelaurence@gmail.com',
            'pin' => '8289',
        ]);
        $response->assertJson(['status' => true]);
        $response->assertStatus(201);
    }
}
