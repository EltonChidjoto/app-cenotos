<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function (): RedirectResponse {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/debug/r2', function (): JsonResponse {
    abort_unless(app()->environment('local'), 404);

    $path = 'debug/'.now()->format('Ymd_His').'_'.Str::uuid().'.txt';
    $content = 'Ola Cloudflare R2 - '.now()->toDateTimeString();

    try {
        $written = Storage::disk('s3')->put($path, $content);

        return response()->json([
            'ok' => true,
            'disk' => 's3',
            'path' => $path,
            'written' => $written,
            'exists' => Storage::disk('s3')->exists($path),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ok' => false,
            'disk' => 's3',
            'path' => $path,
            'error' => $e::class,
            'message' => $e->getMessage(),
        ], 500);
    }
});
