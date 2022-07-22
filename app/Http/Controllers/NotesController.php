<?php

namespace App\Http\Controllers;
use Illuminate\support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\notes;


class notesController extends Controller
{
    // API Function to create notes
    public function CreateNotes(Request $request)
    {
        $request->validate( [
            'Title' => 'required | string | max:50',
            'Description' => 'required | string |max:1000',
        ]);
        
        $notes = notes::create([
            'Title' => $request->Title,
            'Description' => $request->Description
        
        ]);
        return response()->json([
            'message' => 'notes created successfully',
            'notes' => $notes
        ], 200);

    }


    // -----------API Function to display notes-------------------
    public function display_createdNotes()
    {
        $notes = notes::all();
        return response()->json(['success' => $notes]);

    }

    // ------------API Function to display notes by ID------------
    public function display_createdNotes_ID($id)
    {
        $notes = notes::find($id);
        return response()->json(['success' => $notes]);
    }



    // -----------API Function to Update notes by ID--------------
    public function update_createdNotes_ID(Request $request, $id)
    {
       
        //validating the data to make it not to be null
        $request->validate( [
            'Title' => 'required | string | max:50',
            'Description' => 'required | string |max:1000',
        ]);

        $notes = notes::find($id);
        if($notes)
        {
            $notes->Title = $request->Title;
            $notes->Description = $request->Description;
            
            $notes ->update();
            return response()->json(['message'=>'Notes Updated Successfully'],200);
        }
        else
        {
            return response()->json(['message'=>'No Data Found with that ID'],404);
        }
      
    }
    
    

    // -----------API Function to delete notes by ID--------------
    public function delete_createdNotes_ID(Request $request, $id)
    {       
        $notes = notes::find($id);
        if($notes)
        {
            $notes ->delete();
            return response()->json(['message'=>'Data Deleted Successfully'],200);
        }
        else
        {
            return response()->json(['message'=>'No Data Found with that ID'],404);
        }
    }
}
