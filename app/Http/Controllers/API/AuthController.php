<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Mail;
use Illuminate\Mail\Mailable;
class AuthController extends BaseController
{
    public function register(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required',
            'c_password' => 'required | same:password',
            'phone' => 'required',
        ]);

        if($validate->fails()){
            return $this->SendError('Validate Error', $validate->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
	//send the verify code
        $token = Str::random(5);
        try {
            DB::table('users')->where('email', $request['email'])->update([
                'is_verify' => $token,
            ]);
            $email = $request['email'];
            Mail::send('Mails.verify', ['token' => $token], function ($message) use ($email) {
                $message->to($email);
                $message->subject('verify your email');
            });
        } catch (\Exception $exception) {
            return $this->SendError($exception->getMessage(), $exception->getMessage());
        }
        $success['name'] = $user->name;
        $success['is_admin'] = $user->is_admin;
        $success['token'] = $user->createToken('customer')->accessToken;
        return $this->SendResponse($success, 'registerd successfully');
    }

    public function login(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['name'] = $user->name;
            $success['is_admin'] = $user->is_admin;
            $success['token'] = $user->createToken('customer')->accessToken;
            return $this->SendResponse($success, 'logged in successfully');
        }else{
            return $this->SendError('Unauthorised', ['error', 'Unauthorised']);
        }

    }

    //logout
    public function logout(Request $request){
        if(Auth::check()){
            Auth::user()->token()->revoke();
            return $this->SendResponse('logout successfully', 200);
        }else{
            return $this->SendError('something went wrong', 500);
        }
    }

    //To edit the users informations excepts the e-mail.
    public function UpdateUserInformation(Request $request){
	    $user = Auth::user();
	    $validate = Validator::make($request->all(), [
	    	'name' => 'required',
		    'country' => 'required',
		    'phone' => 'required',
	    ]);

	    $user->name = $request->name;
	    $user->country = $request->country;
	    $user->phone = $request->phone;
        $user->save();
	    return $this->SendResponse('Information updated successfully', 200);
    }

    public function Forgot(Request $request){
        $email = $request['email'];
        $validate = Validator::make($request->all(),[
            'email' => 'required | email',
        ]);
        //To check id the email is true or not
        if(User::where('email', $email)->doesntExist()){
            return $this->SendError('Email is not exist', 404);
        }
        //To generate the code that the user enter in the app
        $token = Str::random(5);

        try{
            //To add the code to database to check it letter
            DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            ]);
            //To send the email with the code
            Mail::send('Mails.forgot', ['token' => $token], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Reset you password');
            });

            return $this->SendResponse('Check your email', 200);
        }catch(\Exception $exception){
            return $this->SendError(400, $exception->getMessage());
        }

    }

    public function PasswordReset(Request $request){
        $validate = Validator::make($request->all(),[
            'token' => 'required',
            'password' => 'required',
            'c_password' => 'required | same:password',
        ]);
        $token = $request['token'];
        if (!$passwordResets = DB::table('password_resets')->where('token', $token)->first()) {
            return $this->SendError('Invalid Token', 400);
        }
        if (!$user = User::where('email', $passwordResets->email)->first()) {
            return $this->SendError('User does not exist', 404);
        }
        $user->password = Hash::make($request['password']);
        $user->save();
        return $this->SendResponse('User Password changed successfully', 200);
    }

    public function Emailverify(Request $request){
        $user = Auth::user();
        $validate = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $token = $request['token'];
	if($user->is_verify == $token){
       	    $user->is_verify = 1;
            $user->save();
	    return $this->SendResponse("Email verified", 200);
	    

    }
}
