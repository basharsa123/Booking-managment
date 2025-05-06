<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    /**
     *! Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {

        $validator = FacadesValidator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = new User();
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->save();

        Auth::login($user);
        return response()->json($user, 201);
    }


    /**
    !   Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);


//        if (! $token = auth()->attempt($credentials)) {
//            return response()->json(['error' => 'Unauthorized'], 401);
//        }
        try {
            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
        return $this->respondWithToken($token);
    }

//    public function login(Request $request)
//    {
//        $credentials = $request->only('email', 'password');
//
//        if (empty($credentials['email']) || empty($credentials['password'])) {
//            return response()->json(['error' => 'Email and password are required'], 400);
//        }
//
////        //** authorize the password */
////        if(User::where('email', $credentials['email'])->first()->password == $request->get('password')){
////            return response()->json(["message"=>"the password not verified"]);
////        }
//        //** Attempt to verify the credentials and create a token */
//        try {
//            if (! $token = auth()->attempt($credentials)) {
//                return response()->json(['error' => 'Invalid credentials'], 401);
//            }
//        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
//            return response()->json(['error' => 'Could not create token'], 500);
//        }
//        return $this->respondWithToken($token);
//    }

    /*
     ! Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        if (Auth::user()) {
            return response()->json(Auth::user());
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     *! Log the user out (Invalidate the token).
    !
    @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /*
     ! Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
//        return $this->respondWithToken();
    }

    /*
     ! Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => \Tymon\JWTAuth\Facades\JWTFactory::getTTL(600),
            'user' =>  Auth::user()
        ]);
    }
}
