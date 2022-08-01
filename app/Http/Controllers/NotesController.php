<?php

namespace App\Http\Controllers;
use Illuminate\support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\notes;


class notesController extends Controller
{

    /**
     * @OA\POST(
     *   path="/api/Notes",
     *   summary="Creating Notes",
     *   description="Creating Notes",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"Title","Description"},
     *               @OA\Property(property="Title", type="string"),
     *               @OA\Property(property="Description", type="string"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Notes Added Successfully"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

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



     /**
     * @OA\GET(
     *   path="/api/displayNotes",
     *   summary="display Notes",
     *   description="display Notes data",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // -----------API Function to display notes-------------------
    public function display_createdNotes()
    {
        $notes = notes::all();
        return response()->json(['success' => $notes]);

    }


     /**
     * @OA\GET(
     *   path="/api/displayNotes/{id}",
     *   summary="displaying Notes",
     *   description="Display Notes Based on ID",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="No Notes Found with That ID to Display"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

    // ------------API Function to display notes by ID------------
    public function display_createdNotes_ID($id)
    {
        $notes = notes::find($id);
        if($notes)
        {
            return response()->json(['success' => $notes]);
        }
        else
        {
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['Message' => "No Notes found with that ID"]);
        }
    }



      /**
     * @OA\POST(
     *   path="/api/updateNotes/{id}",
     *   summary="Updating Notes",
     *   description="Update Notes based on ID",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"Title","Description"},
     *               @OA\Property(property="Title", type="string"),
     *               @OA\Property(property="Description", type="string"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Notes Updated Successfully"),
     *   @OA\Response(response=401, description="No Notes Found with that ID to Update"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

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
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['message'=>'No Notes Found with that ID'],404);
        }
      
    }
    
    

    /**
     * @OA\DELETE(
     *   path="/api/deleteNotes/{id}",
     *   summary="Delete Notes",
     *   description="delete users notes by ID",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="Notes Deleted Successfully"),
     *   @OA\Response(response=401, description="No Notes Found with that ID to Delete"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

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
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['message'=>'No Data Found with that ID'],404);
        }
    }
}
