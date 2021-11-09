<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\Models\User;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles');
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     description="Получение профиля авторизованного пользователя",
     *     tags={"auth"},
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает свойства пользователя",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="user",
     *                      type="object",
     *                      ref="#/components/schemas/User"),
     *              ),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     description="Получение всех пользователей",
     *     tags={"users"},
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает пользователей",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/User"),
     *              ),
     *          ), 
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ответ если пользователь не найден",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function allUsers()
    {
         return response()->json(['users' =>  User::all()], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     description="Получение пользователя с ИД",
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ИД пользователя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращает свойства пользователя",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/User"),
     *          ) 
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ответ если пользователь не найден",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function singleUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => '0x00404', 'Message' => 'User not found!'));
            return response()->json($responseObject, 404);
        }

    }

}