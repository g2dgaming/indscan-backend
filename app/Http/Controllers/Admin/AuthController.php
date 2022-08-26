<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function register(Request $request)
    {
        $validateUser = Validator::make($request->all(), 
        [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("Temp Device Token")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function handle2Fa(Request $request){
        $google2fa = new Google2FA();
        $response=['success'=>false];
        $user=Auth::user();
        if(!$user->has_setup_2fa){
            $key=$google2fa->generateSecretKey(32);            
            $user->google2fa_secret=$key;
            $response['success']=true;
            $response['secret_key']=$key;
            $user->save();
        }
        return response()->json($response);
    }
    public function verify2Fa(Request $request){
        $google2fa = new Google2FA();
        $user=Auth::user();
        $secret=$request['secret_key'];
        $response=[];
        $valid = $google2fa->verifyKey($user->google2fa_secret, $secret,0);        
        //$valid=true;
        if($valid ){
            if(!$user->has_setup_2fa){
                $user->has_setup_2fa=true;
                $user->{'2fa_verified_at'}=\Carbon\Carbon::now();
                $user->save();
            }
            $response['token'] =$user->createToken("DEVICE API TOKEN")->plainTextToken;            
        }
        $response['success']=$valid;
        return response()->json($response);
    }
}