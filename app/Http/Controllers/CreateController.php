<?php

namespace App\Http\Controllers;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\create;
use Illuminate\Support\Str;
use App\Models\PasswordReset as ModelsPasswordReset;
use App\Models\PasswordReset;

use App\Notifications\PasswordResetRequest;


class CreateController extends Controller
{
    //Function to Create Data
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
        return response()->json(['message'=>'Data Added Successfully'],200);
    }

    //Function to Retreive Data Based on ID 
    public function display_by_id($id)
    {
        $create = create::find($id);
        return response()->json(['success' => $create]);
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
            return response()->json(['message'=>'No Data Found with that ID'],404);
        }
    }
//----------------------------------------------------------------------






    public function reset(Request $request){
        $request->validate([
            'userId' => 'required',
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
            Log::channel('custom')->error("Check your old password");
            return response()->json(['message'=>"Check your old password", 'status'=>400]);
        }
    }



public function forgotPassword(Request $request)
    {  
        
         $request->validate([
            'Email'=>'required | max:200',         
        ]);

        $Email = $request->Email;

        //validate email
        //$validator = Validator::make($Email, [
          //  'Email' => 'required|Email'
        //]);

        $create = create::where('Email', $Email)->first();
        if (!$create) {
            return response()->json(['Message' => "Email does not exists", 'status' => 404]);
            
        } 
        else {
            //$name = $create->UserName;

            //$token = Auth::fromcreate($create);
            $token = Str::random(10);
            $reset = new ModelsPasswordReset();
    
            $reset->Email = $request->input('Email');
            $reset->token = $request->input('token');
    
            Mail::to($Email)->send(new SendMail($token, $Email));
            return $this->successResponse();
            /*
            if ($create) {
                $delay = now()->addSeconds(120);
                $create->notify((new PasswordResetRequest($create->Email, $token))->delay($delay));
                Log::info('Reset Password Token Sent to your Email');
                return response()->json([
                    'message' => 'Reset Password Token Sent to your Email',
                    'delay' => $delay,
                ], 201);
            }*/
        }
     
    }

    //-------------------
    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'require'
        ]);

       // $validator = Validator::make($request->all(), [
         //   'new_password' => 'required|string|min:6|max:50',
           // 'password_confirmation' => 'required|same:new_password',
        //]);


        $passwordReset = PasswordReset::where('token', $token)->first();
        if(!$passwordReset){
            return response()->json(['message' => "Token is invalid "]);
        }

        $user = User::where('email', $passwordReset->email)->first();
        $user->password = Hash::make($request->password);

        PasswordReset::where('email', $user->email)->delete();
        return "Password changed";

        /*
            $currentUser = JWTAuth::authenticate($request->token);
        {
            if (!$currentUser) {
                log::warning('Invalid Authorisation Token ');
                throw new FundoNotesException('Invalid Authorization Token', 401);
            } else {
                $user = User::updatePassword($currentUser, $request->new_password);
                log::info('Password updated successfully');
                return response()->json([
                    'message' => 'Password Reset Successful'
                ], 201);
            }
        } */
    }
}
