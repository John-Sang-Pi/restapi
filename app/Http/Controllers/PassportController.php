<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

class PassportController extends Controller
{
    public function register(Request $request)
    {
    	$data = $request->all();
    	$validator = Validator::make($data, [
    		'name' => 'required|min:3',
    		'email' => 'required|email|unique:users',
    		'password' => 'required|min:6'
    	]);

    	if($validator->fails()) {
    		return response()->json($validator->errors(), 400);
    	}

    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password)
    	]);

    	$token = $user->createToken('API Test')->accessToken;

    	return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
    	$credentials = [
    		'email' => $request->email,
    		'password' => $request->password
    	];

    	if(auth()->attempt($credentials)) {
    		$token = auth()->user()->createToken('API Login')->accessToken;
    		return response()->json(['token' => $token], 200);
    	} 
    	else 
    		return response()->json(['error' => 'Unauthorized!'], 401);

    }

    public function details()
    {
    	return response()->json(['user' => auth()->user()], 200);
    }
}
