<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Auth;



/**
 * Class Controller
 * @package App\Http\Controllers
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="0.0.2",
 *         title="Computer inventory API",
 *         @OA\License(name="MIT"),
 *         @OA\Contact(
 *             email="plsatin@yandex.ru",
 *             name="Павел Сатин"
 *         )
 *     ),
 * )
 */
class Controller extends BaseController
{




    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Авторизация по email и паролю для получения токена",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * )
     */


    /**
     * @OA\Schema(
     *      schema="Response",
     *      type="object",
     *      @OA\Property(
     *          property="Response",
     *          type="string",
     *          description="Ответ: успешно или не успешно",
     *      ),
     *      @OA\Property(
     *          property="data",
     *          type="object",
     *          description="Объект с ответом",
     *          @OA\Property(
     *              property="Code",
     *              type="string",
     *              description="Код ошибки",
     *          ),
     *          @OA\Property(
     *              property="Message",
     *              type="string",
     *              description="Сообщение с описанием ошибки",
     *          ),
     *      ),
     * )
     */




    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

}
