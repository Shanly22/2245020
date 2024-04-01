<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess()
    {
        $this->post('/api/v1/users', [
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]) ->assertStatus(201)
        ->assertJson([
            "data"=>[
                "name"=>"John Doe",
                "email"=> "john.doe@gmail.com"
            ]
            ]);
    }

    public function testRegisterFailed()
     {
         $this ->post('/api/v1/users', [
             'name' => '',
             'email' => '',
             'password' => '',
             'password_confirmation' => ''
         ]) ->assertStatus(400)
         ->assertJson([
            "errors"=>[
                "name"=>[
                    "The name field is required."
                ],
                "email"=>[
                    "The email field is required"
                ],
                "password"=>[
                    "The password field is required"
                ]
            ]
        ]);
     }
     public function testRegisterFailedValidation()
     {
        $this->testRegisterSuccess();
        $this->post('/api/v1/users', [
            'name' => 'Mitchell Admin',
            'email' => 'john.doe@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ])->assertStatus(400)
        ->assertJson([
            "errors"=>[
                "email"=>[
                    "The email has already been taken"
                ]
            ]
        ]);
     }

     public function testLoginSuccess()
     {
        $this->testRegisterSuccess();
        $this->post('/api/v1/users/login', [
            'email' => 'john.doe@gmail.com',
            'password' => '123456789'
        ])->assertStatus(200)
            ->assertJson([
                "data"=>[
                    "name"=>"John Doe",
                    "email"=>"john.doe@gmail.com"
                ]
            ]);
        
            $user = User::where('email', 'john.doe@gmail.com')-> first();
            self::assertNotNull($user->remember_token);
     }

     public function testLoginFailedEmail()
     {
        $this->post('/api/v1/users/login', [
            'email' => 'john.doe@gmail.com',
            'password' => '123456789'
        ])->assertStatus(401)
            ->assertJson([
                "errors"=>[
                    "message"=>['username or password wrong']
                ]
            ]);
     }

     public function testLoginFailedPassword()
     {
        $this->testRegisterSuccess();
        $this->post('/api/v1/users/login', [
            'email' => 'john.doe@gmail.com',
            'password' => 'password123456789'
        ])->assertStatus(401)
            ->assertJson([
                "errors"=>[
                    "message"=>['username or password wrong']
                ]
            ]);
     }

}
