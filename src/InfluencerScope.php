<?php

namespace Microservices;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class InfluencerScope
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
        if ($this->userService->isInfluencer()) {
           return $next($request);
        }

        throw new AuthenticationException;
    }
}
