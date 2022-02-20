<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User;

use Illuminate\Support\Facades\Auth;
use Exception;


class AuthController extends Controller
{



    /**
     * @OA\Post(
     *     path="/api/register",
     *     description="Регистрация пользователя",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         description="Объект пользователя",
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Имя пользователя",
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      format="email",
     *                      description="Email пользователя",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      format="password",
     *                      description="Пароль пользователя",
     *                  ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Возвращает свойства пользователя",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/User"),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Ответ если регистрация завершилась с ошибкой",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     * )
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

        } catch (Exception $e) {
            //return error message
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => '0x00409', 'Message' => 'User Registration Failed!'));
            return response()->json($responseObject, 409);

        }
    }



    /**
     * @OA\Post(
     *     path="/api/login",
     *     description="Авторизация пользователя",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         description="Объект пользователя",
     *         required=true,
     *         @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      format="email",
     *                      description="Email пользователя",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      format="password",
     *                      description="Пароль пользователя",
     *                  ),
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает свойства пользователя",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      @OA\Property(
     *                          property="token",
     *                          type="string",
     *                          description="Токен",
     *                      ),
     *                      @OA\Property(
     *                          property="token_type",
     *                          type="string",
     *                          description="Тип токена",
     *                      ),
     *                      @OA\Property(
     *                          property="expires_in",
     *                          type="integer",
     *                          description="Срок действия токена",
     *                      ),
     *                  ),
     *              ),
     *          ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Авторизация пользователя завершилась с ошибкой",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     * )
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

        $responseObject = array('Response' => 'OK', 'data' => array('Code' => '0x00200', 'Message' => 'Successfully logged out'));
        return response()->json($responseObject, 200);
    }





}