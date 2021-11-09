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
     *     @OA\Response(
     *         response="204",
     *         description="Ответ если произошла ошибка",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(ref="#/components/schemas/Response"),
     *          ),
     *     ),
     *     security={{ "apiAuth": {} }}
     * )
     */    
    public function profile()
    {
        try {
            
            return response()->json(['user' => Auth::user()], 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

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
     *         response="204",
     *         description="Ответ если произошла",
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
        try {

            $users = User::all();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

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
     *         response="204",
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

            return response()->json($user, 200);

        } catch (\Exception $e) {
            $responseObject = array('Response' => 'Error', 'data' => array('Code' => $e->getCode(), 'Message' => $e->getMessage()));
            return response()->json($responseObject, 204);
        }

    }

}