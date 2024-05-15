<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|min:8',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }

    //     $user = User::create($request->all());
    //     return response()->json($user);
    // }

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }

    //     $user = User::where('email', $request->email)->first();
    //     if ( isset( $user ) ) {
    //         if ( Hash::check( $request->password, $user->password ) ) {
    //             $token = $user->createToken('auth_token')->plainTextToken ;
    //             return response()->json([
    //                 'message' => 'Connected Successfully',
    //                 'token' => $token
    //             ]) ;
    //         } else {
    //             return response()->json(['message' => 'Invalid Password']);
    //         }
    //     } else {
    //         return response()->json(['message' => 'Invalid Email']);
    //     }
    //     return response()->json($user);
    // }

}
