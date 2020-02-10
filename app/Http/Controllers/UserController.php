<?php

namespace App\Http\Controllers;

use App\User;
use App\UserImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\JWTManager as JWT;
use DB;

class UserController extends Controller
{
    //using jwt auth verification so this is not required
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }
    public function getAllUser()
    {
        $allUsers = User::all();
        $userImages = UserImages::all();
        
        return array('users' => $allUsers, 'userImages' => $userImages);
    }

    public function register(Request $request)
    {
        $messages = [
            'identity.required' => 'Email or username cannot be empty',
            'email.unique' => 'Email is already registered',
            'username.unique' => 'Username is already registered',
            'password.required' => 'Password cannot be empty',
            'password.min' => 'Password must be of atleast 8 characters.',
            'password_confirmation.required' => 'Confirm Password cannot be empty',
            'password.confirmed' => 'Passwords did not match.',
        ];
        $validData = $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            // regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/
            'password_confirmation' => 'required'
        ], $messages);

        if ($validData) {
            $user = new User;
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->password_confirmation = Hash::make($request->input('password_confirmation'));
            $user->save();
            $token = JWTAuth::fromUser($user);
            
            /*adding default profile pictures */
            $userImages = new UserImages;
            $userImages->profile_image = "profile.png";
            $userImages->user_id = $user->id;
            $userImages->save();
        
            // return response()->json(['message' => 'Registration Successful', compact('user', 'token')], 201);
            return response()->json(['success' => true, 'message' => 'Registration Successful', 'user' => $user, 'token' => $token], 201);
        }
    }

    public function login(Request $request)
    {
        //checking if user has send email type or text type through input identity
        $login = request()->input('identity');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);

        $credentials = $request->only($field, 'password');
        
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Username or Password'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(['success' => true, 'token'=> $token]);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['Your token has expired. Please Login again.'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['Token is Invalid. Please Login again.'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['Token is not available. Please Login again.'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    public function resetPassword(Request $request, $email)
    {
        $messages = [
            'password.required' => 'Password cannot be empty',
            'password.min' => 'Password must be of atleast 8 characters.',
            'password_confirmation.required' => 'Confirm Password cannot be empty',
            'password.confirmed' => 'Passwords did not match.',
        ];
        $validData = $this->validate($request, [
            'password' => 'required|min:8'
        ], $messages);

        $user = User::where('email', $email)->first();

        if ($user && $validData) {
            $user->password = Hash::make($request->input('password'));
            $user->password_confirmation = Hash::make($request->input('password_confirmation'));
            $user->save();
            $token = JWTAuth::fromUser($user);

            return response()->json(['email_exist' => true, 'message' => 'Password reset Successful']);
        } else {
            return response()->json(['email_exist' => false, 'error' => 'Given email does not exist. Please check your email.']);
        }
    }


    public function resetRole(Request $request, $id, $data)
    {
        $user = User::findOrFail($id);

        if ($user) {
            $user->isAdmin = $data;
            $user->save();

            return response()->json(['message' => 'Role updated.']);
        } else {
            return response()->json(['error' => 'ID not found.']);
        }
    }

    public function getUserById($id)
    {
        $userById = User::findOrFail($id);
        return $userById;
    }

//     public function userProfileImage()
//     {
//         echo url("/storage/profile_images/");
//     }
}
