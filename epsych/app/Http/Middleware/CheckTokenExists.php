<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExists
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Сначала пытаемся получить токен из сессии
        $token = $request->session()->get('access_token');

        // Если токена нет в сессии, пытаемся получить его из запроса
        if (!$token) {
            $token = $request->route('token');

            // Сохраняем токен в сессии для последующего использования
            if ($token) {
                $request->session()->put('access_token', $token);
            }
        }

        // Если токен отсутствует и в запросе, возвращаем 403 Forbidden
        if (!$token) {
            abort(403, __('Доступ запрещен'));
        }

        // Проверяем, существует ли токен в базе данных
        $exists = AccessToken::where('token', $token)->exists();

        if (!$exists) {
            // Если токен не найден, возвращаем 403 Forbidden
            abort(403, __('Доступ запрещен'));
        }

        // Если токен найден, продолжаем выполнение запроса
        return $next($request);
    }
}
