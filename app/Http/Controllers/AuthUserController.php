<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(
            $this->user->all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('API Token')->plainTextToken;
        $user->token = $token;

        return response()->json([new UserResource($user)], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ], 200);
    }

    public function validateToken(Request $request)
    {
        if ($token = $request->bearerToken()) {
            $user = auth('sanctum')->user();
            $user->token = $token;
            return response()->json([new UserResource($user)], 200);
        }
    }

    public function logout()
    {
        $user = Auth()->$this->user();
        $user->tokens()->delete();

        return response(['message' => 'Logout realizado com sucesso.'], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->user->find($id);
        if (!$user) {
            return response()->json(["error" => '404 Not Found'], 404);
        }
        return response()->json([new UserResource($user)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return response()->json(["error" => '404 Not Found'], 404);
        }

        $user->update($request->all());
        return response()->json([new UserResource($user)], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return response()->json(["error" => '404 Not Found'], 404);
        }

        $user->delete();
        return response()->json([], 204);
    }
}
