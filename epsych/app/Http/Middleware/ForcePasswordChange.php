<?php

namespace App\Http\Middleware;

use App\Contracts\User\UserPasswordExpirationServiceContract;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    protected UserPasswordExpirationServiceContract $passwordExpirationService;

    public function __construct(UserPasswordExpirationServiceContract $passwordExpirationService)
    {
        $this->passwordExpirationService = $passwordExpirationService;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $this->passwordExpirationService->isPasswordExpired($user)) {
            return redirect(route('profile.show'))->with('status', __('Ваш пароль устарел, пожалуйста, обновите его, чтобы дальше пользоваться сервисом.'));
        }

        return $next($request);
    }
}
