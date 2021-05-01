<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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
        $success['name'] = $user->name;
        $success['token'] = $user->createToken('customer')->accessToken;
        return $this->SendResponse($success, 'Customer registerd successfully');
    }

    public function login(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['name'] = $user->name;
            $success['token'] = $user->createToken('customer')->accessToken;
            return $this->SendResponse($success, 'Customer logged in successfully');
        }else{
            return $this->SendError('Unauthorised', ['error', 'Unauthorised']);
        }

    }

    //Admin login and register
    public function AdminRegister(Request $request){
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
        $success['name'] = $user->name;
        //the brackets is to give the admin all permissions
        $success['token'] = $user->createToken('admin', ['*'])->accessToken;
        return $this->SendResponse($success, 'Admin registerd successfully');
    }

    public function AdminLogin(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['name'] = $user->name;
            $success['token'] = $user->createToken('admin', ['*'])->accessToken;
            return $this->SendResponse($success, 'Admin logged in successfully');
        }else{
            return $this->SendError('Unauthorised', ['error', 'Unauthorised']);
        }
    }

    //To edit the users informations excepts the e-mail.
    public function UpdateUserInformation(Request $request, $id){
	    $user = User::find($id);
	    $validate = Validator::make($request->all(), [
	    	'name' => 'required',
		    'country' => 'required',
		    'phone' => 'required',
	    ]);

	    if($id != Auth::id()){
		    return $this->SendError('You do not have permisssion', 500);
	    }
	    $user->name = $request->name;
	    $user->country = $request->country;
	    $user->phone = $request->phone;
        $user->save();
	    return $this->SendResponse('Information updated successfully', 200);
    }

    public function PasswordReset(Request $request){}
    public function EmailVerify(){}
}
