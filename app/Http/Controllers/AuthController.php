<?php
namespace App\Http\Controllers;
use App\Models\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function Admingapswregister(Request $request)
    {
    $validated=Validator::make($request->all(),[
        'name'=>'Required',
        'email'=>'required|email|unique:users',
        'password'=>'required|confirmed',
      

    ]);

    if($validated->failed()){
        return response()->json($validated->errors(),400);

    }else{
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role="Admin";
    $user->save();
    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
        'user' => $user,
        'token' => $token
    ];

    return response($response, 201); }
    }
    public function gapswregister(Request $request)
    {
    $validated=Validator::make($request->all(),[
        'name'=>'Required',
        'email'=>'required|email|unique:users',
        'password'=>'required|confirmed',
      

    ]);

    if($validated->failed()){
        return response()->json($validated->errors(),400);

    }else{
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role="user";
    $user->save();
    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
        'user' => $user,
        'token' => $token
    ];

    return response($response, 201); }
    }

    public function logingapsw(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
   
        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }
      

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function getUser()
    {
        return response()->json(auth()->user());
    } 
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function createuser(Request $request)
    {
    echo "hey";
    }

}
