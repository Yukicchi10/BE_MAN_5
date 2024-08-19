<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedControllerDosen extends BaseController
{

    public function username()
    {
        return 'email';
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $Dosen = Dosen::where('email', $request->email)->first();
            if (!$Dosen) {
                return $this->sendError('username', $this->username() . ' not found', 401);
            }
            if (!Auth::attempt($request->only($this->username(), 'password'), $request->remember_me)) {
                return $this->sendError('password', 'Wrong Password', 401);
            }

            $token = $Dosen->createToken('token')->plainTextToken;
            $dataUser = [
                'id' => $Dosen->idDosen,
                'name' => $Dosen->nama,
                'email' => $Dosen->email,
                'token' => $token
            ];

            $response = [
                'user' => $dataUser
            ];

            return $this->sendResponse($response, 'User login successfully.');
        } catch (\Throwable $th) {
            return $this->sendError('Unauthorized.', ['error' => 'Unautorized'], 401);
        }
    }

    /**
     * Handle logout request.
     */
    public function destroy()
    {
        Auth::guard('web')->logout();
        $Dosen = request()->user();
        $Dosen->tokens()->delete();

        return $this->sendResponse([], 'User logout successfully.');
    }

    //

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Data Tidak Ditemukan, Silahkan Daftar Terlebih Dahulu'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {   $user = Auth::user()->id;
        $dosen = Dosen::where('id_user', $user)->first();
        $dosen->email = Auth::user()->email;
        return $this->sendResponse($dosen, "siswa retrieved successfully");
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function refresh()
    // {
    //     return $this->respondWithToken(auth()->refresh());
    // }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
