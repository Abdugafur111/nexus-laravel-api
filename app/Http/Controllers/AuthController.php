<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;

    public function register(Request $request)
{
    $fields = $request->validate([
        'name' => 'required|string',
        'email' => 'required|string|unique:users,email',
        'password' => 'required|string|confirmed'
    ]);

    $user = User::create([
        'name' => $fields['name'],
        'email' => $fields['email'],
        'password' => bcrypt($fields['password'])
    ]);

    $token = $user->createToken('myapptoken')->plainTextToken;
    $response = [
        'user' => $user,
        'token' => $token
    ];

    return response()->json($response, 201);
}

/*
JSON-register
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "created_at": "2023-03-17T12:09:58.000000Z",
        "updated_at": "2023-03-17T12:09:58.000000Z"
    },
    "token": "eyJpdiI6IjV..."
}
*/


public function login(Request $request)
{
    $fields = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string'
    ]);

    $user = User::where('email', $fields['email'])->first();

    if (!$user || !Hash::check($fields['password'], $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('myapptoken')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
}

/*
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "created_at": "2023-03-17T12:00:00.000000Z",
        "updated_at": "2023-03-17T12:00:00.000000Z"
    },
    "token": "eyJ1c2VybmFtZSI6IkpvaG4gRG9lIiwidG9rZW5fdHlwZSI6IkpXVCJ9.eyJpYXQiOjE2MzU1MTk1MTksImV4cCI6MTYzNTUyMzExOSwic3ViIjoiMSJ9.Cm0n8Vw1GXTL-6KsU6-K7KPWJPdM3q7VylbgA69uB-M"
}

*/


public function logout(Request $request)
{
    auth()->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out']);
}

/*{
    "message": "Logged out"
}
 */

}
