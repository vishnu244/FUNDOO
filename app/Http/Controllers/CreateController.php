<?php

namespace App\Http\Controllers;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\create;
use App\Models\User;
use App\Mail\sendmail;
//-----
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;


use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class CreateController extends Controller
{

     /**
     * @OA\POST(
     *   path="/api/create",
     *   summary="Creating data",
     *   description="Creating Data",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"UserName","Email", "Password", "MobileNumber", "Address"},
     *               @OA\Property(property="UserName", type="string"),
     *               @OA\Property(property="Email", type="email"),
     *               @OA\Property(property="Password", type="password"),
     *               @OA\Property(property="MobileNumber", type="int"),
     *               @OA\Property(property="Address", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Data Added Successfully"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    //API Function to Create Data
    public function store(Request $request)
    {
        //validating the data to make it not to be null
        $request->validate([
            'UserName'=>'required | max:200',
            'Email'=>'required | max:200',
            'Password'=>'required | max:200',
            'MobileNumber'=>'required | max:12',
            'Address'=>'required | max:200',
        ]);
        $create = new create;
        $create->UserName = $request->UserName;
        $create->Email = $request->Email;
        //Password Encrytion using HASH methodd
        $create->Password = $request->Password;
        $create->Password = Hash::make($create->Password);

        $create->MobileNumber = $request->MobileNumber;
        $create->Address = $request->Address;

        $create ->save();
        Log::channel('custom')->info("Data Added Successfully");
        return response()->json(['message'=>'Data Added Successfully'],200);

    }

     /**
     * @OA\GET(
     *   path="/api/displaydata_by_ID/{id}",
     *   summary="displaying data",
     *   description="Display Data Based on ID",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="No data Found with That ID"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    //Function to Retreive Data Based on ID 
    public function display_by_id($id)
    {
        $create = create::find($id);
        if($create)
        {
            return response()->json(['success' => $create]);
        }
        else{
            Log::channel('custom')->error(" No Data found with that ID");
            return response()->json(['Message' => "No data Found with That ID"]);
        }

    }

     /**
     * @OA\GET(
     *   path="/api/displaydata",
     *   summary="display data",
     *   description="display users data",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="Invalid credentials"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    

    //Function to Retreive Data 
    public function display()
    {
        $create = create::all();
        return response()->json(['success' => $create]);
    }



     /**
     * @OA\POST(
     *   path="/api/updatedata_by_ID/{id}",
     *   summary="Updating data",
     *   description="Update Data by ID",
     *   @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"UserName","Email", "Password", "MobileNumber", "Address"},
     *               @OA\Property(property="UserName", type="string"),
     *               @OA\Property(property="Email", type="email"),
     *               @OA\Property(property="Password", type="password"),
     *               @OA\Property(property="MobileNumber", type="int"),
     *               @OA\Property(property="Address", type="string"),
     *            ),
     *        ),
     *    ),
     *   @OA\Response(response=200, description="Data Updated Successfully"),
     *   @OA\Response(response=401, description="No Data Found with that ID"),
     *   
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

    //Function to Update Data based on ID
    public function update_by_id(Request $request, $id)
    {
        //validating the data to make it not to be null
        $request->validate([
            'UserName'=>'required | max:200',
            'Email'=>'required | max:200',
            'Password'=>'required | max:200',
            'MobileNumber'=>'required | max:12',
            'Address'=>'required | max:200',
        ]);
        $create = create::find($id);
        if($create)
        {
            $create->UserName = $request->UserName;
            $create->Email = $request->Email;
            //Password Encrytion using HASH methodd
            $create->Password = $request->Password;
            $create->Password = Hash::make($create->Password);

            $create->MobileNumber = $request->MobileNumber;
            $create->Address = $request->Address;

            $create ->update();
            return response()->json(['message'=>'Data Updated Successfully'],200);
        }
        else
        {
            Log::channel('custom')->error("No Data Found with that ID");
            return response()->json(['message'=>'No Data Found with that ID'],404);
        }
    }


     /**
     * @OA\DELETE(
     *   path="/api/deletedata_by_ID/{id}",
     *   summary="Delete data",
     *   description="delete users data by ID",
     *   @OA\RequestBody(
     *    ),
     *   @OA\Response(response=201, description="success"),
     *   @OA\Response(response=401, description="No Data Found with that ID"),
     * )
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */

    //Function to Delete Data based on ID
    public function delete_by_id(Request $request, $id)
    {
       
        $create = create::find($id);
        if($create)
        {
            $create ->delete();
            return response()->json(['message'=>'Data Deleted Successfully'],200);
        }
        else
        {
            Log::channel('custom')->error("No Data Found with that ID");
            return response()->json(['message'=>'No Data Found with that ID'],404);
        }
    }

//****************************************************************** */


//changing password in postman

    public function changePassword(Request $request){
        $request->validate([
            'email' => 'required',
            'password' =>'required',
            'newPassword' => 'required'
        ]);
        $result = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if($result){
            User::where('id', $request->userId)->update(['password' => Hash::make($request->newPassword)]);
            return response()->json(['message'=>"password updated successfully", 'status'=>200]);
            
        }
        else{
            Log::channel('custom')->error("You have Entered the wrong password");
            return response()->json(['message'=>"Check your old password", 'status'=>400]);
        }
    }


//--- sending token to mail to change password ------- 
public function forgotPassword(Request $request)
    {  
        
         $request->validate([
            'email'=>'required | max:200',         
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::channel('custom')->error("Email does not exists");
            return response()->json(['Message' => "Email does not exists", 'status' => 404]);
            
        } 
        else {

            $token = Str::random(10);
            $reset = new PasswordReset();

            PasswordReset::create([
                'email' => $request->email,
                'token' => $token
            ]);

            Mail::to($email)->send(new SendMail($token, $email));
            
            return "mail sent";
            
        }
        
     
    }

    //-------------------
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'token' => 'required'
        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if(!$passwordReset){
            Log::channel('custom')->error("You have entered invalid token");
            return response()->json(['message' => "Token is invalid "]);
        }

        $user = User::where('email', $passwordReset->email)->first();
        $user->password = Hash::make($request->password);

        PasswordReset::where('email', $request->email)->delete();
        return "Password changed";

       
    }


}
