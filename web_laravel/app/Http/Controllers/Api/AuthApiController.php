<?php
/*
* Nombre de la clase         : AuthApiController.php
* Descripción de la clase    : Controlador API para gestionar la autenticación de usuarios de la app móvil,
*                               incluyendo registro, login, logout y obtención de información del usuario.
* Fecha de creación          : 16/12/2024
* Elaboró                    : Claude Code Assistant
* Fecha de liberación        : 16/12/2024
* Autorizó                   : Alan Basilio
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Registro de nuevo usuario para la aplicación móvil
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);

            // Crear el usuario
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Generar token de autenticación
            $device = $request->header('X-Device-Name') ?: 'flutter-app';
            $token  = $user->createToken($device, ['*'])->plainTextToken;

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'token'   => $token,
                'token_type' => 'Bearer',
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'tiene_cliente' => false,
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    /**
     * Login de usuario
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciales inválidas',
                ], 401);
            }

            $user = User::where('email', $credentials['email'])->first();

            // Generar token de autenticación
            $device = $request->header('X-Device-Name') ?: 'flutter-app';
            $token  = $user->createToken($device, ['*'])->plainTextToken;

            return response()->json([
                'message' => 'Autenticación exitosa',
                'token'   => $token,
                'token_type' => 'Bearer',
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'tiene_cliente' => $user->tieneCliente(),
                    'cliente_info' => $user->infoCliente(),
                ],
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    /**
     * Obtener información del usuario autenticado
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'tiene_cliente' => $user->tieneCliente(),
                'cliente_info'  => $user->infoCliente(),
                'avatar_url'    => $user->avatarUrl(),
                'iniciales'     => $user->initials(),
            ],
        ], 200);
    }

    /**
     * Logout del usuario (elimina el token actual)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente',
        ], 200);
    }
}
