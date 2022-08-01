<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//use PHPUnit\Framework\TestCase;

class CreateControllerTest extends TestCase
{
    
    //Testcase case for Successful registration
    public function test_registration()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/registration', [
            "name" => "vardhan",            
            "email" => "vardhan@gmail.com",
            "password" => "vardhan123",          
        ]);
        $response->assertStatus(201)->assertJson(['message' => 'User successfully registered']);
    }


    //Testcase case for UnSuccessful registration
    public function test_registration_failed()
    {

        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/registration', [
            "name" => "CRUD",            
            "email" => "pqr@gmail.com",//using the same mail return mail has already taken
            "password" => "crud123",          
        ]);
        $response->assertStatus(422)->assertJson(['message' => 'The email has already been taken.']);
    }


    // Testcase for successfull Login
    public function test_login()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/login',
            [
                "email" => "pqr@gmail.com",
                "password" => "crud123"
            ]
        );
        $response->assertStatus(200);
    }


    // Testcase for Unsuccessfull Login    
    public function test_login_Failed()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/login',
            [
                "email" => "pqr@gmail.com",
                "password" => "pqr12" //giving a wrong password fails to login
            ]
        );
        $response->assertStatus(401)->assertJson(['message' => 'Invalid Credentials']);
    }



    // Testcase for successfull Logout
    public function test_Logout()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/logout', [
            "token" => '3|rRIhM5rFaXDFvwC0Uv4tcs83CqSrLUlfSDbriYhX'
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Logged Out Successfully']);
    }

    // Testcase for successfull Logout
    public function test_Logout_failed()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/logout', [
            "token" => '3|rRIhM5rFaXDFvwC0Uv4tcs83CqSrLUlfSDbriYhX'
        ]);

        $response->assertStatus(401)->assertJson(['message' => 'Unauthenticated.']);
    }


    // Testcase for Create
    public function test_Create()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/create', [
            "UserName" => 'Vishnu vardhan',
            "Email" => 'mighty@gmail.com',
            "Password" => 'mighty',
            "MobileNumber" => '7890654321',
            "Address" => 'Andhra Pradesh',
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Data Added Successfully']);
    }


    //-------------------------------------------
    // Testcase for Retreiving all data
    public function test_Retreive()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/displaydata', []
            
        );

        $response->assertStatus(200);
    }


    // Testcase for Retreiving specific data
    public function test_Retreivedata_ID()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/displaydata_by_ID/5', []
            
        );

        $response->assertStatus(200);
    }


    // Testcase for Updata Data
    public function test_Update()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/updatedata_by_ID/1', 
        [
            "UserName" => 'Vishnu vardhan',
            "Email" => 'mighty@gmail.com',
            "Password" => 'mighty',
            "MobileNumber" => '7890654321',
            "Address" => 'Andhra Pradesh',
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Data Updated Successfully']);
    }


    // Testcase for Updata invalid Data 
    public function test_Update_invalid()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/updatedata_by_ID/15', //ID15 is not created so data can't be updated with that id
        [
            "UserName" => 'Vishnu vardhan',
            "Email" => 'mighty@gmail.com',
            "Password" => 'mighty',
            "MobileNumber" => '7890654321',
            "Address" => 'Andhra Pradesh',
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'No Data Found with that ID']);
    }


    // Testcase for Delete Data
    public function test_Delete()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('Delete', '/api/deletedata_by_ID/1
        '
        );
        $response->assertStatus(200)->assertJson(['message' => 'Data Deleted Successfully']);
    }

    // Testcase for Delete Invalid Data
    public function test_Delete_invalid()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('Delete', '/api/deletedata_by_ID/15', //ID15 is not created so data can't be Deleted with that id
        [
            
        ]);
        $response->assertStatus(404)->assertJson(['message' => 'No Data Found with that ID']);
    }


    public function test_Change_Password()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/reset', 
        [
            'email' => 'ABC@gmail.com',
            'password' =>'vishnu123',
            'newPassword' => 'vishnu1234',
            
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'password updated successfully']);
    }


    public function test_Forgot_Password()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/forgotPassword', 
        [
            'email' => 'mightyvishnumec244@gmail.com',
            
            
        ]);
        $response->assertStatus(200);
    }


    public function test_Reset_Password()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/forgotPassword', 
        [
            'email' => 'mightyvishnumec244@gmail.com',
            'password' => 'vishnu123',
            'token' => 'oNzwENnVc7'
                        
        ]);
        $response->assertStatus(200);
    }
}

