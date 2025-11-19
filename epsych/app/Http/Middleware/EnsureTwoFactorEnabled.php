<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        // Если пользователь не включил 2FA и не находится на профиле
        if ($request->user()
            && !$request->user()->two_factor_secret
            && Route::currentRouteName() !== 'profile.show') {

            return redirect(route('profile.show'))->with('status', __('Пожалуйста, включите двухфакторную аутентификацию.'));
        }

        return $next($request);
    }
}
