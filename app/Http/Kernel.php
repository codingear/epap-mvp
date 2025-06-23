<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /** @var array<string, class-string|string> */
    protected $routeMiddleware = [
        // ...existing middleware...
        'check.role' => \App\Http\Middleware\CheckRole::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'student.profile.complete' => \App\Http\Middleware\StudentProfileCompleteCheck::class,
    ];
}