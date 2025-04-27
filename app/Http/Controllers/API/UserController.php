<?php

namespace App\Http\Controllers\API;

use App\Models\Api\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'updated_at');
        $sortTo = $request->get('sort_to', 'desc');

        $query = User::query()->orderBy($sortBy, $sortTo);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return UserResource::collection($query->paginate($perPage));
    }

    public function show(User $user): JsonResource
    {
        return UserResource::make($user);
    }

    public function store(CreateUserRequest $request): JsonResource
    {
        $userData = $request->validated();

        $userData['is_admin'] = true;
        $userData['email_verified_at'] = now()->format('Y-m-d H:i:s');
        $userData['password'] = Hash::make($userData['password']);
        $userData['created_by'] = request()->user()->id;
        $userData['updated_by'] = request()->user()->id;

        $user = User::create($userData);

        return UserResource::make($user);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResource
    {
        $userData = $request->validated();

        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $userData['updated_by'] = request()->user()->id;

        $user->update($userData);

        return UserResource::make($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
