<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\auth\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $body =  $request->safe();
        if (Auth::attempt(['email' => $body->email, 'password' => $body->password])) {
            $user = User::where('email', $body->email)->firstOrFail();
            $token = $user->createToken($user->email . "_first Party")->plainTextToken;
            $refreshToken = $user->createToken($user->email . "_first Party", [], Carbon::now()->addMinutes(10))->plainTextToken;
            return response()->json([
                'token' => $token,
                'refresh_token' => $refreshToken,
                'user' => $user
            ], Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'Unauthorized'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function register(RegisterRequest $request)
    {
        $body =  $request->safe();
        $user = User::create([
            'name' => $body->name,
            'email' => $body->email,
            'password' => Hash::make($body->password),
        ]);
        $token = $user->createToken($user->email . "_first Party")->plainTextToken;
        $refreshToken = $user->createToken($user->email . "_first Party", [], Carbon::now()->addMinutes(10))->plainTextToken;
        return response()->json([
            'token' => $token,
            'refresh_token' => $refreshToken,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'lu kenapa logout kontol'
        ], Response::HTTP_OK);
    }

    public function refresh(Request $request)
    {
        $authorizationHeader  = $request->header('Authorization');
        $token = explode('|', $authorizationHeader)[1];
        $match = DB::table('personal_access_tokens')->where('token', Hash('sha256', $token))->first();
        // ini udah dapet match refresh token.
        // besok terusin create token baru dan refresh token berdasarkan tokenable_id dari refresh token tersebut
        return $match;
    }
}
