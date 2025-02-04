<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Librarian;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Регистрация пользователя
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Пользователь зарегистрирован'], 201);
    }

    // Авторизация пользователя
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Неверные данные'], 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::parseToken(); // Проверяем, есть ли токен
            JWTAuth::invalidate($token); // Инвалидируем токен
            return response()->json(['message' => 'Вы успешно вышли'], 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            \Log::error('Ошибка выхода: токен недействителен - ' . $e->getMessage());
            return response()->json(['error' => 'Токен уже недействителен'], 400);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            \Log::error('Ошибка выхода: токен истек - ' . $e->getMessage());
            return response()->json(['error' => 'Токен истек'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            \Log::error('Ошибка выхода: ' . $e->getMessage());
            return response()->json(['error' => 'Токен не найден'], 400);
        } catch (\Exception $e) {
            \Log::error('Ошибка выхода: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }
    
    // Регистрация библиотекаря
    public function registerLibrarian(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:librarians',
            'password' => 'required|string|min:6'
        ]);

        $librarian = Librarian::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Библиотекарь зарегистрирован'], 201);
    }

    // Логин библиотекаря
    public function loginLibrarian(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!$token = Auth::guard('librarian')->attempt($credentials)) {
            return response()->json(['error' => 'Неверные данные'], 401);
        }
    
        return response()->json(['token' => $token]);
    }

     // Выход библиотекаря
    public function logoutLibrarian()
    {
        try {
            $token = JWTAuth::parseToken(); // Получаем текущий токен из заголовков запроса
            JWTAuth::invalidate($token); // Инвалидируем токен, тем самым "выходя" из системы
            return response()->json(['message' => 'Вы успешно вышли'], 200);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            \Log::error('Ошибка выхода: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка выхода'], 500);
        }
    }
}
