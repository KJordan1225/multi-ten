<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
         // Append tenancy middleware to the existing "web" group
        $middleware->appendToGroup('web', [
            InitializeTenancyByDomain::class,        // resolves tenant via Host header
            PreventAccessFromCentralDomains::class,  // blocks central domains from tenant routes
        ]);

        // (Optional) If you want path-based tenancy for API:
        // use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
        // $middleware->appendToGroup('api', [
        //     InitializeTenancyByPath::class,
        // ]);

        // (Optional) Define aliases if you want to use them in route groups
        // $middleware->alias([
        //     'tenant.domain' => InitializeTenancyByDomain::class,
        // ]);
    })    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
