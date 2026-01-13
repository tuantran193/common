<?php

namespace Microservices;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class AdminScope
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->userService->isAdmin()) {
           return $next($request);
        }

        throw new AuthenticationException;
    }
}
