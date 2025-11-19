<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNoToken
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, есть ли токен в сессии
        if (!$request->session()->has('access_token')) {
            // Если токен отсутствует, перенаправляем на страницу авторизации
            return redirect()->route('student-login'); // Замените 'login' на ваше реальное имя маршрута авторизации
        }

        // Если токен есть, продолжаем выполнение запроса
        return $next($request);
    }
}
