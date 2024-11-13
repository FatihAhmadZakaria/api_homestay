<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'nama_depan' => 'required|string|max:255',
            'nama_belakang' => 'required|string|max:255',
            'password' => 'required|string|min:4',
            'phone' => 'required|string|max:20',
        ]);

        $user = User::create([
            'email' => $request->email,
            'nama_depan' => $request->nama_depan,
            'nama_belakang' => $request->nama_belakang,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
        ]);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
        ], 201);
    }

    /**
     * Log in the user and create a token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek apakah pengguna terdaftar
        if (Auth::attempt($validated)) {
            $user = Auth::user();  // Mendapatkan pengguna yang sedang login
            $token = $user->createToken('YourAppName')->accessToken;  // Membuat token untuk pengguna

            // Mengirimkan response dengan access_token dan id_user
            return response()->json([
                'access_token' => $token,
                'id_user' => $user->id,  // Menyertakan id_user untuk keperluan pemesanan
                'nama_depan' => $user->nama_depan,
                'email' => $user->email,
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401); // Jika login gagal
    }


    /**
     * Log out the user and revoke the token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
{
    if ($user = $request->user()) {
        // Hapus semua token pengguna
        $user->tokens()->delete();

        // Hapus remember_token
        $user->remember_token = null;
        $user->save();

        return response()->json([
            'message' => 'User logged out successfully.',
        ]);
    }

    return response()->json([
        'message' => 'No authenticated user found.',
    ], 401);
}


}
