<?php

namespace App\Http\Controllers;
use Illuminate\support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\notes;

use Illuminate\Support\Facades\Log;

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
        Log::channel('custom')->info("Notes Displayed successfully");

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
            Log::channel('custom')->info("Notes Displayed successfully based on ID");
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
            Log::channel('custom')->info("Notes Updated Successfully");
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
            Log::channel('custom')->info("Data Deleted Successfully");
        }
        else
        {
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['message'=>'No Notes Found with that ID'],404);
        }
    }


    //----------------Function to Add Pin to Notes----------------------
    /**
     * @OA\POST(
     *   path="/api/pinNotesById",
     *   summary="Pin Notes by ID",
     *   description="Pin Notes by ID",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id"},
     *               @OA\Property(property="id", type="integer"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Notes pinned Successfully"),
     *   @OA\Response(response=404, description="No Notes Found with that ID"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function pinNotesById(Request $request)
    {
        $request->validate( [
            'id' => 'required | integer',
        ]);

        $notesObject = new Notes();
        $notes = $notesObject->noteId($request->id);

        if (!$notes) {
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['message'=>'No Notes Found with that ID'],404);
        }
        if ($notes->pin == 0)
        {
            if ($notes->archive == 1) {
                $notes->archive = 0;
                $notes->save();
            }
            $notes->pin = 1;
            $notes->save();

            Log::channel('custom')->info('Notes pinned successfully');
            return response()->json(['message' => 'Notes pinned Successfully'], 201);                          
        }
    }


    //--------------------Function to UnPin Notes---------------------------
    /**
     * @OA\POST(
     *   path="/api/UnpinNotesById",
     *   summary="UnPin Notes by ID",
     *   description="UnPin Notes by ID",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id"},
     *               @OA\Property(property="id", type="integer"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Notes Unpinned Successfully"),
     *   @OA\Response(response=404, description="No Notes Found with that ID"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function UnpinNotesById(Request $request)
    {
        $request->validate( [
            'id' => 'required | integer',
        ]);

        $notesObject = new Notes();
        $notes = $notesObject->noteId($request->id);

        if (!$notes) {
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['message'=>'No Notes Found with that ID'],404);
        }
        if ($notes->pin == 1)
        {
            
            $notes->pin = 0;
            $notes->save();

            Log::channel('custom')->info('Notes Unpinned successfully');
            return response()->json(['message' => 'Notes Unpinned Successfully'], 201);                          
        }
    }



    
    //--------------------Function to Archive Notes----------------------------
    /**
     * @OA\POST(
     *   path="/api/ArchieveNotesById",
     *   summary="Archive Notes by ID",
     *   description="Archive Notes by ID",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id"},
     *               @OA\Property(property="id", type="integer"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Notes Archived Successfully"),
     *   @OA\Response(response=404, description="No Notes Found with that ID"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function ArchieveNotesById(Request $request)
    {
        $request->validate( [
            'id' => 'required | integer',
        ]);

        $notesObject = new Notes();
        $notes = $notesObject->noteId($request->id);

        if (!$notes) {
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['message'=>'No Notes Found with that ID'],404);
        }
        if ($notes->archive == 0)
        {
            if($notes->pin == 1){
                $notes->pin =0;
                $notes -> save();
            }
            
            $notes->archive = 1;
            $notes->save();

            Log::channel('custom')->info('Notes Archived successfully');
            return response()->json(['message' => 'Notes Archived Successfully'], 201);                          
        }
    }


    //------------------Function to UnArchive Notes----------------------------
    /**
     * @OA\POST(
     *   path="/api/UnArchiveNotesById",
     *   summary="UnArchive Notes by ID",
     *   description="UnArchive Notes by ID",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"id"},
     *               @OA\Property(property="id", type="integer"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=201, description="Notes UnArchived Successfully"),
     *   @OA\Response(response=404, description="No Notes Found with that ID"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function UnArchiveNotesById(Request $request)
    {
        $request->validate( [
            'id' => 'required | integer',
        ]);

        $notesObject = new Notes();
        $notes = $notesObject->noteId($request->id);

        if (!$notes) {
            Log::channel('custom')->info("No Notes Found with that ID");
            return response()->json(['message'=>'No Notes Found with that ID'],404);
        }
        if ($notes->archive == 1)
        {
            
            $notes->archive = 0;
            $notes->save();

            Log::channel('custom')->info('Notes UnArchived successfully');
            return response()->json(['message' => 'Notes UnArchived successfully'], 201);                          
        }
    }



}
