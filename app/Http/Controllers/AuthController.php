<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User;

use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json($user, 201);

        } catch (\Exception $e) {
            //return error message
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => '0x00409', 'Message' => 'User Registration Failed!'));
            return response()->json($responseObject, 409);

        }
    }



    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => '0x00401', 'Message' => 'Unauthorized'));
            return response()->json($responseObject, 401);
        }

        return $this->respondWithToken($token);
    }


    public function logout () {
        Auth::logout();

        $responseObject = array('Response' => 'Error', 'data' => array('Code' => '0x00200', 'Message' => 'Successfully logged out'));
        return response()->json($responseObject, 200);
    }





}