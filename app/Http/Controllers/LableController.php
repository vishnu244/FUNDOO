<?php

namespace App\Http\Controllers;
use Illuminate\support\Facades\DB;
use Illuminate\support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\lable;
use App\Models\notes;


class LableController extends Controller
{

    
     /**
     * @OA\POST(
     *   path="/api/lable",
     *   summary="Creating lable",
     *   description="Creating lable",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"lable_id","lable"},
     *               @OA\Property(property="lable_id", type="int"),
     *               @OA\Property(property="lable", type="string"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Lable Added Successfully"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

        // -----------API Function to Create Lable------------------
    public function CreateLable(Request $request)
    {
        $request->validate( [
            'lable_id' => 'required | integer ',
            'lable' => 'required | string |max:100',
        ]);
        
        $lable = lable::create([
            'lable_id' => $request->lable_id,
            'lable' => $request->lable,
        ]);
        return response()->json([
            'message' => 'Lable Added successfully',
            'lable' => $lable
        ], 200);

    }

    /**
     * @OA\GET(
     *   path="/api/displayLable",
     *   summary="display Lable",
     *   description="display Lable data",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

    // -----------API Function to display Lable-------------------
    public function display_CreateLable()
    {
        $lable = lable::all();
        return response()->json(['success' => $lable]);
    }


     /**
     * @OA\GET(
     *   path="/api/displayLable/{id}",
     *   summary="displaying Lables",
     *   description="Display Lable Based on ID",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="No Lable Found with That ID to Display"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

    // ------------API Function to display Lable by ID------------
    public function display_CreateLable_ID($id)
    {
        $lable = lable::find($id);
        if($lable)
        {
            return response()->json(['success' => $lable]);
        }
        else
        {
            Log::channel('custom')->info("No Lable Found with that ID");
            return response()->json(['Message' => "No Lable Found with that ID"]);
        }
    }




        
     /**
     * @OA\POST(
     *   path="/api/updateLable/{id}",
     *   summary="Updating lable",
     *   description="Update lable based on ID",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"lable_id","lable"},
     *               @OA\Property(property="lable_id", type="int"),
     *               @OA\Property(property="lable", type="string"),
     *               
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Lable Updated Successfully"),
     *   @OA\Response(response=401, description="No Lable Found with that ID to Update"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
     // -----------API Function to Update Lable by ID--------------
    public function update_CreateLable_ID(Request $request, $id)
    {
       
        //validating the data to make it not to be null
        $request->validate( [
            'lable_id' => 'required | integer ',
            'lable' => 'required | string |max:100',
        ]);
        
        $lable = lable::find($id);
        if($lable)
        {
            $lable->lable_id = $request->lable_id;
            $lable->lable = $request->lable;
            
            $lable ->update();
            return response()->json(['message'=>'Lable Updated Successfully'],200);
        }
        else
        {
            Log::channel('custom')->info("No Lable Found with that ID");
            return response()->json(['message'=>'No Lable Found with that ID'],404);
        }
      
    }
    
    


    /**
     * @OA\DELETE(
     *   path="/api/deleteLable/{id}",
     *   summary="Delete data",
     *   description="delete users data by ID",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="Lable Deleted Successfully"),
     *   @OA\Response(response=401, description="No Lable Found with that ID to Delete"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

    // -----------API Function to delete Lable by ID--------------
    public function delete_CreateLable_ID(Request $request, $id)
    {       
        $lable = lable::find($id);
        if($lable)
        {
            $lable ->delete();
            return response()->json(['message'=>'Lable Deleted Successfully'],200);
        }
        else
        {
            Log::channel('custom')->info("No Lable Found with that ID");
            return response()->json(['message'=>'No Lable Found with that ID'],404);
        }
    }


        // -----------API Function to JoinTable  by ID--------------
    public function JoinTables()
    {
        $join = DB::table('notes')->join('lables', 'notes.id','=','lables.lable_id')->select('notes.*','lables.lable')->get();
        return $join;
    }
}
