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


class CreateController extends Controller
{
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

    //Function to Retreive Data 
    public function display()
    {
        $create = create::all();
        return response()->json(['success' => $create]);
    }


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

        //validate email
        //$validator = Validator::make($Email, [
          //  'Email' => 'required|Email'
        //]);

        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::channel('custom')->error("Email does not exists");
            return response()->json(['Message' => "Email does not exists", 'status' => 404]);
            
        } 
        else {
            //$name = $create->UserName;

            //$token = Auth::fromcreate($create);
            $token = Str::random(10);
            $reset = new PasswordReset();

            PasswordReset::create([
                'email' => $request->email,
                'token' => $token
            ]);
    
            //$reset->email = $request->input('email');
            //$reset->token = $request->input('token');
    
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

       // $validator = Validator::make($request->all(), [
         //   'new_password' => 'required|string|min:6|max:50',
           // 'password_confirmation' => 'required|same:new_password',
        //]);


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
    //**************************************************************** */


}
