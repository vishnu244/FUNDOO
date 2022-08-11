<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotesControllerTest extends TestCase
{
    //Testcase case for creatingNotes
    public function test_CreateNotes()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
        ->json('POST', '/api/Notes', [
            'Title' => 'Self-Introduction',
            'Description' => 'A self introduction essay is a type of an essay used by an individual to introduce himself.',
                     
        ]);
        $response->assertStatus(200)->assertJson(['message' => 'notes created successfully']);
    }
    

    //Testcase case for Update createNotes
    public function test_Update_CreateNotes()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/updateNotes/5', [
                'Title' => 'Intro',
                'Description' => 'A self introduction essay is a type of an essay used by an individual to introduce himself.I am eager to learn about this.Lets start!',  
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'notes created successfully']);
    }

    //Testcase case for Update invalid createNotes
    public function test_Update_CreateNotes_invalid()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/updateNotes/1', [
                'Title' => 'Intro',
                'Description' => 'A self introduction essay is a type of an essay used by an individual to introduce himself.I am eager to learn about this.Lets start!',  
                
                
            ]);
        $response->assertStatus(404)->assertJson(['message' => 'No Data Found with that ID']);
    }
    

    //Testcase case for delete createNotes
    public function test_Delete_CreateNotes()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('DELETE', '/api/deleteNotes/2', [
                'Title' => 'Intro',
                'Description' => 'A self introduction essay is a type of an essay used by an individual to introduce himself.I am eager to learn about this.Lets start!',  
                
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'Data Deleted Successfully']);
    }


    //Testcase case to PIN createdNotes
    public function test_pin_Notes()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/pinNotesById', [
                'id' => '1',
                
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'Notes pinned Successfully']);
    }


    //Testcase case to UnPIN createdNotes
    public function test_UnPin_Notes()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/UnpinNotesById', [
                'id' => '1',
                
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'Notes Unpinned Successfully']);
    }


    //Testcase case to Archive createdNotes
    public function test_Archive_Notes()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/ArchieveNotesById', [
                'id' => '1',
                
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'Notes Archived Successfully']);
    }


    //Testcase case to UnArchive createdNotes
    public function test_UnArchive_Notes()
    {
        $response = $this->withHeaders([
            'Content-Type' => 'Application/json',
        ])
            ->json('POST', '/api/UnArchiveNotesById', [
                'id' => '1',
                
            ]);
        $response->assertStatus(200)->assertJson(['message' => 'Notes UnArchived successfully']);
    }

}
