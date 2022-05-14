<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  /**
   * Handle an incoming registration request.
   *
   * @param  \Illuminate\Http\Request  $request
   */
  public function register(Request $request)
  {
    $fields = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|confirmed|min:8',
    ]);

    $user = User::create([
      'name' => $fields['name'],
      'email' => $fields['email'],
      'password' => Hash::make($fields['password']),
    ]);

    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
      'user' => $user,
      'token' => $token
    ];

    return response($response, 201);
  }

  public function login(Request $request)
  {
    $fields = $request->validate([
      'email' => 'required|string|email|max:255',
      'password' => 'required|string|min:8',
    ]);

    $user = User::where('email', $fields['email'])->first();

    if(!$user || !Hash::check($fields['password'], $user->password)) {
      return response([
        'nessage' => 'Bad creds'
      ], 401);
    }
    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
      'user' => $user,
      'token' => $token
    ];

    return response($response, 201);
  }

  public function logout(Request $request) {
    auth()->user()->tokens()->delete();
    return [
      'message' => 'Logged out'
    ];
  }
}
