<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     //GIVEN : the precondition fr test to work
            //--> user is authenticated
     //WHEN : the action to take
            //-->post request create product, user etc
     //THEN : the outcome
            //-->product exists
    public function test_example()
    {
        // $response = $this->json('POST','/api/v1/userss',[

        // ]);
        $response = $this->get('/api/testAssertion');
        $response->assertStatus(200);
    }
}
