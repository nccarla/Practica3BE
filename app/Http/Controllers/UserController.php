<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\PartialUpdateUserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $showOnlyTrashed = $request->boolean('trashed') || $request->boolean('is_trashed');

        if ($showOnlyTrashed) {
            $query->onlyTrashed();
        }

        if ($request->filled('email')) {
            $query->where('email', $request->get('email'));
        }

        if ($request->filled('username')) {
            $query->where('username', $request->get('username'));
        }

        $users = $query->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8);

        if (! isset($data['hiring_date'])) {
            $data['hiring_date'] = now()->toDateString();
        }

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage (full update).
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update($data);

        return response()->json(UserResource::make($user), 200);
    }

    /**
     * Partially update the specified resource in storage.
     */
    public function partialUpdate(PartialUpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update($data);

        return response()->json(UserResource::make($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'El usuario ha sido eliminado correctamente.',
        ], 200);
    }

    public function restore(int $id)
    {
        $user = User::onlyTrashed()->find($id);

        if (! $user) {
            return response()->json([
                'message' => 'Usuario no encontrado entre los eliminados.',
            ], 404);
        }

        $user->restore();

        return response()->json([
            'message' => 'Usuario restaurado correctamente.',
        ], 200);
    }
}
