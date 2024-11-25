<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

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
            'password' => Hash::make($request->password),
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
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'id_user' => $user->id,
            'nama' => $user->nama_depan . ' ' . $user->nama_belakang,
            'email' => $user->email,
        ]);
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

    // Fungsi untuk memperbarui kata sandi
    public function updatePassword(Request $request)
    {
        Log::info('Entering updatePassword function');

        $request->validate([
            'sandi_lama' => 'required',
            'sandi_baru' => 'required|min:4',
        ]);

        $user = Auth::user();

        // Verifikasi kata sandi lama
        if (!Hash::check($request->sandi_lama, $user->password)) {
            return response()->json([
                'message' => 'Kata sandi lama salah.',
            ], 400);
        }

        // Update kata sandi
        $user->password = Hash::make($request->sandi_baru);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui.',
        ], 200);
    }

    public function gantiNomor(Request $request)
    {
        Log::info('Entering updatePhone function');
        Log::info('Before validation');

        try {
            $request->validate([
                'no_lama' => 'required',
                'no_baru' => 'required|unique:users,phone',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }

        Log::info('After validation');

        $user = Auth::user();
        Log::info('User Auth:', ['user' => $user->toArray()]);
        Log::info('Request received:', $request->all());

        // Verifikasi nomor telepon lama
        if ($user->phone !== $request->no_lama) {
            return response()->json([
                'message' => 'Nomor telepon lama tidak cocok.',
            ], 400);
        }

        // Update nomor telepon
        $user->phone = $request->no_baru;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Nomor telepon berhasil diperbarui.',
        ], 200); // Ubah status ke 200 (success) sesuai dengan praktik standar
    }

}
