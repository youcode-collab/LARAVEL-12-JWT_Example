<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Kyojin\JWT\Facades\JWT;


Route::post('/register', [AuthController::class, 'register']);


Route::middleware('jwt')->group(function () {
    Route::get('/me', function (Request $request) {
        $token = $request->bearerToken();
        $payload = JWT::decode($token);

        return response()->json([
            'user' => Auth::user(),
            'payload' => $payload,
        ]);
    });

});
