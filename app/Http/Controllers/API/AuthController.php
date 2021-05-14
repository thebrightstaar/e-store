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
use File;

class AuthController extends BaseController
{
    public function register(Request $request){
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required | email',
            'password' => 'required',
            'c_password' => 'required | same:password',
            'phone' => 'required',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:5048',
            'country' => 'required',
        ]);

        if($validate->fails()){
            return $this->SendError('Validate Error', $validate->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        if($request->has('photo')){
            $newImageName = time() . '_' . $request->photo->getClientOriginalName();
            $request->photo->move('uploads/ProfilePictures', $newImageName);
            $imageURL = url('uploads/ProfilePictures'.'/'.$newImageName);
            DB::table('users')->where('email', $request['email'])->update([
                'photo' => $imageURL,
            ]);
        }
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
            return $this->SendError($exception->getMessage(), 400);
        }
        $userData = User::where('email', $request['email'])->first();
        $success['name'] = $userData->name;
        $success['is_admin'] = $userData->is_admin;
        $success['photo'] = $userData->photo;
        $success['token'] = $userData->createToken('customer')->accessToken;
        return $this->SendResponse($success, 'registerd successfully');
    }

    public function login(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->is_admin == 1) {
                $token = Str::random(5);
                DB::table('users')->where('email', $request['email'])->update([
                    'is_verify' => $token,
                ]);
                $email = $request['email'];
                Mail::send('Mails.verify', ['token' => $token], function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('verify your email');
                });
                return $this->SendResponse('verify your email', 200);
            }
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->is_verify != 1) {
                return $this->SendError('Verify your email', 400);
            }
            $success['name'] = $user->name;
            $success['is_admin'] = $user->is_admin;
            $success['photo'] = $user->photo;
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
    public function updateUserInformation(Request $request){
	    $user = Auth::user();
	    $validate = Validator::make($request->all(), [
	    	'name' => 'required',
		    'country' => 'required',
		    'phone' => 'required',
	    ]);
        if($validate->fails()){
            return $this->SendError('Validate Error', $validate->errors());
        }
        if ($request->has('photo')) {
            $newImageName = time() . '_' . $request->photo->getClientOriginalName();
            $oldImage = substr($user->photo, 22);
            File::delete(public_path($oldImage));
            $request->photo->move('uploads/ProfilePictures', $newImageName);
            $imgaeURL = url('/uploads/ProfilePictures'.'/'.$newImageName);
            $user->photo = $imgaeURL;
        }
	    $user->name = $request->name;
	    $user->country = $request->country;
	    $user->phone = $request->phone;
        $user->save();
	    return $this->SendResponse('Information updated successfully', 200);
    }

    public function forgot(Request $request){
        $email = $request['email'];
        $validate = Validator::make($request->all(),[
            'email' => 'required | email',
        ]);
        if($validate->fails()){
            return $this->SendError('Validate Error', $validate->errors());
        }
        //To check id the email is true or not
        if(User::where('email', $email)->doesntExist()){
            return $this->SendError('Email is not exist', 404);
        }
        //To generate the code that the user enter in the app
        $token = Str::random(5);

        try{
            if (DB::table('password_resets')->where('email', $request['email'])->first()) {
                DB::table('password_resets')->where('email', $request['email'])->update([
                    'token' => $token,
                ]);
                $email = $request['email'];
                Mail::send('Mails.forgot', ['token' => $token], function ($message) use ($email){
                    $message->to($email);
                    $message->subject('Reset your password');
                    $message->priority(1);
                });
                return $this->SendResponse('check your email', 200);
            }
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
            return $this->SendError($exception->getMessage(), 400);
        }

    }

    public function passwordReset(Request $request){
        $validate = Validator::make($request->all(),[
            'token' => 'required',
            'password' => 'required',
            'c_password' => 'required | same:password',
        ]);
        if($validate->fails()){
            return $this->SendError('Validate Error', $validate->errors());
        }
        $token = $request['token'];
        if (!$passwordResets = DB::table('password_resets')->where('token', $token)->first()) {
            return $this->SendError('Invalid Token', 400);
        }
        if (!$user = User::where('email', $passwordResets->email)->first()) {
            return $this->SendError('User does not exist', 404);
        }
        $user->password = Hash::make($request['password']);
        DB::table('password_resets')->where('token', $request['token'])->delete();
        $user->save();
        return $this->SendResponse('User Password changed successfully', 200);
    }

    public function emailVerify(Request $request){
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
        ]);
        if ($validate->fails()) {
            return $this->SendError('Validate Error', $validate->errors());
        }
        $user = User::where('email', '=', $request->email)->first();
        $token = $request['token'];
        if ($user->is_verify == $token) {
            $user->is_verify = 1;
            $user->markEmailAsVerified();
            $user->save();
            if ($user->is_Admin == 1) {
                $success['name'] = $user->name;
	            $success['is_Admin'] = $user->is_Admin;
                $success['photo'] = $user->photo;
                $success['token'] = $user->createToken('customer')->accessToken;
		        return $this->SendResponse($success, 200);
            }
            return $this->SendResponse('Email Verified', 200);
        }
        if ($user->is_verify == 1) {
            return $this->SendError('Email is already Verified', 400);
        }
        return $this->SendError('Wrong token', 404);
    }

    public function resendCode(Request $request){
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        $email = $request['email'];
        $token = Str::random(5);
        DB::table('password_resets')->where('email', $email)->update([
            'token' => $token,
        ]);
        Mail::send('Mails.forgot', ['token' => $token], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Reset you password');
        });

        return $this->SendResponse('Check your email', 200);
    }
}
