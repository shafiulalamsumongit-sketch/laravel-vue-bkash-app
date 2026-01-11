<?php

// This is an example snippet. Do NOT replace your Kernel.php with this directly.
// Open app/Http/Kernel.php in your project and ensure the 'api' middleware group looks like this:


protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\SetLocale::class,
    ],
    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];

