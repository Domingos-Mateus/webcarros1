<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function register(Request $request){

    	$data = $request->validate([
    		'name' => 'required|string|max:191',
    		'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|string',
    	]);


    	$user = User::create([
    		'name' => $data['name'],
            'email' => $data['email'],
    		'password' => Hash::make($data['password'])
    	]);

    	$token = $user->createToken('authToken')->plainTextToken;

    	$response = [
    			'user'=>$user,
    			'token'=>$token
    	];

    	return response($response, 201);


    }

    public function logout(){
    	 $user = Auth::user();
    	 $user->tokens()->delete();
    	auth()->user()->tokens()->delete();
    	return response(['message'=>'logout feito com sucesso']);
    }

    public function login(Request $request){

    	$data = $request->validate([
    		'email' => 'required|max:191',
    		'password' => 'required|string',
    	]);

    	$user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
    		return response(['message'=> 'credencial inválida'], 401);
    	} else {
    		$token = $user->createToken('authTokenLogin')->plainTextToken;
    		$response = [
    			'user'=>$user,
    			'token'=>$token
    	];

    	return response($response, 200);
    	}

    }

    public function teste(){
        return 'testou..';
    }
}
