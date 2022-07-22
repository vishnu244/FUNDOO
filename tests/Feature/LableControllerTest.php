<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LableControllerTest extends TestCase
{
    //Testcase case for creatingLable
    public function test_CreateLable()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/lable', [
            "lable_id" => "2",            
            "lable" => "xyz",
                     
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'Lable Added successfully']);
    }
    

    //Testcase case for Update createLable
    public function test_Update_CreateLable()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/updateLable/6', [
                "lable_id" => "6",
                "lable" => "Vishnu Vardhan",
                
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'Lable Updated successfully']);
    }

    //Testcase case for Update invalid createLable
    public function test_Update_CreateLable_invalid()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/updateLable/1', [
                "lable_id" => "8",
                "lable" => "Vishnu Vardhan",
                
            ]);
        $response->assertStatus(404)->assertJson(['message' => 'No Lable Found with that ID']);
    }
    

    //Testcase case for delete createLable
    public function test_Delete_CreateLable()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('DELETE', '/api/deleteLable/4', [
              
                
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'Lable Deleted Successfully']);
    }
}
