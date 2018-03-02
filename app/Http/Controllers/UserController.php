<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    function index(Request $request){

    	if($request->isJson()){
    		$users = User::all();
       		return response()->json($users, 200);
    	}
    		
    	return response()->json(['error' => 'Unauthorized'], 401);
    	
    	
    }

    function createUser(Request $request){

    	if($request->isJson()){

    		try {
    			$data = $request->json()->all();
	    		$user = User::create([
	    			'name' => $data['name'],
	    			'email' => $data['email'],
	    			'password' => Hash::make($data['password']),
	    			'api_token' => str_random(60)
	    		]);

	    		return response()->json($user, 201);
    		} catch (QueryException $e) {
    			return response()->json(['error' => 'Bad request'], 400);
    		}
    		
    	}

    	return response()->json(['error' => 'Unauthorized'], 401);

    }

    function findUser(Request $request, $id){
    	
    	if($request->isJson()){
    		try {
    			$user = User::findOrFail($id);

    			return response()->json($user, 200);
    		} catch(ModelNotFoundException $e){
    			return response()->json(['error' => 'User not found.'], 404);
    		}

    	}

    	return response()->json(['error' => 'Unauthorized'], 401);
    }

    function updateUser(Request $request, $id) {

    	if ($request->isJson()){
    		try {
    			$user = User::findOrFail($id);
    			$data = $request->json()->all();

    			$user->name = $data['name'];
    			$user->email = $data['email'];
    			$user->password = Hash::make($data['password']);

    			$user->save();

    			return response()->json($user, 200);
    		} catch(ModelNotFoundException $e){
    			return response()->json(['error' => 'User not found.'], 404);
    		}
    	}

    	return response()->json(['error' => 'Unauthorized'], 401);
    }

    function deleteUser(Request $request, $id) {
    	if($request->isJson()){
    		try {
    			$user = User::findOrFail($id);
    			$user->delete();
    			return response()->json($user, 200);
    		} catch(ModelNotFoundException $e){
    			return response()->json(['error' => 'User not found.'], 404);
    		}

    	}

    	return response()->json(['error' => 'Unauthorized'], 401);
    }

    function getToken(Request $request){
    	if ($request->isJson()){
    		try {
	    		$this->validate($request, [
			        'email' => 'required',
			        'password' => 'required'
		         ]);

    			$data = $request->json()->all();

    			$user = User::where('email', $data['email'])->first();

    			if ($user && Hash::check($data['password'], $user->password))
    				return response()->json($user, 200);
    			else
    				return response()->json(['error' => 'User or password are wrong'], 406);
    		}catch(ModelNotFoundException $e){
    			return response()->json(['error' => 'User or password are wrong'], 406);
    		}catch(ValidationException $e){
    			return response()->json(['error' => 'Email and password field are required'], 422);
    		}
    	}

    	return response()->json(['error' => 'Unauthorized'], 401);
    }
}
