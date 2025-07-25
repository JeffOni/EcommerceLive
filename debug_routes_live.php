<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

// Ruta de debug específica para capturar el problema en tiempo real
Route::get('/debug-live', function () {
    $user = auth()->user();

    $debug = [
        'timestamp' => now()->toISOString(),
        'authenticated' => Auth::check(),
        'user_data' => $user ? [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified' => $user->hasVerifiedEmail(),
            'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : null,
            'roles' => $user->roles->pluck('name')->toArray(),
            'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ] : null,
        'gates' => Auth::check() ? [
            'admin-panel' => Gate::allows('admin-panel'),
            'manage-users' => Gate::allows('manage-users'),
            'verify-payments' => Gate::allows('verify-payments'),
        ] : [],
        'session' => [
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'all_session_data' => session()->all(),
        ],
        'middlewares_test' => Auth::check() ? [
            'auth' => true,
            'can_admin_panel' => Gate::allows('admin-panel'),
            'would_pass_admin_middleware' => Auth::check() && Gate::allows('admin-panel'),
        ] : [],
        'urls' => [
            'current' => request()->url(),
            'dashboard' => route('dashboard'),
            'admin_dashboard' => route('admin.dashboard'),
            'welcome' => route('welcome.index'),
        ],
        'request_info' => [
            'method' => request()->method(),
            'path' => request()->path(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]
    ];

    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
})->middleware('web');

// Ruta de debug para probar acceso directo al admin
Route::get('/debug-admin-direct', function () {
    return response()->json([
        'message' => 'Si ves esto, puedes acceder a rutas con middleware admin',
        'user' => auth()->user()?->name,
        'timestamp' => now()->toISOString()
    ]);
})->middleware(['web', 'auth', 'can:admin-panel']);

// Ruta para simular el flujo del dashboard
Route::get('/debug-dashboard-flow', function () {
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }

    $result = [
        'user' => $user->name,
        'has_admin_roles' => $user->hasAnyRole(['admin', 'super_admin']),
        'should_redirect_to_admin' => false,
        'redirect_url' => null,
    ];

    // Simular la lógica del dashboard exacta
    if ($user && $user->hasAnyRole(['admin', 'super_admin'])) {
        $result['should_redirect_to_admin'] = true;
        $result['redirect_url'] = '/admin';
        return response()->json($result)->header('X-Should-Redirect', '/admin');
    } else {
        $result['redirect_url'] = route('welcome.index');
        return response()->json($result)->header('X-Should-Redirect', route('welcome.index'));
    }
})->middleware(['web', 'auth:sanctum', config('jetstream.auth_session'), 'verified']);

// Middleware de logging para capturar requests
Route::middleware(['web'])->group(function () {
    Route::get('/debug-middleware-trace', function () {
        return response()->json([
            'message' => 'Middleware trace successful',
            'middlewares_passed' => request()->route()?->middleware() ?? [],
            'user' => auth()->user()?->name ?? 'Not authenticated'
        ]);
    });
});

echo "Rutas de debug agregadas:\n";
echo "- /debug-live - Estado completo en tiempo real\n";
echo "- /debug-admin-direct - Prueba acceso directo con middlewares admin\n";
echo "- /debug-dashboard-flow - Simula lógica del dashboard\n";
echo "- /debug-middleware-trace - Traza middlewares\n";
