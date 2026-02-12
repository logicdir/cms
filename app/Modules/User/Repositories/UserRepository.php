<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function allPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = User::with('roles');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', fn($q) => $q->where('slug', $filters['role']));
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?User
    {
        return User::with('roles')->findOrFail($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => $data['status'] ?? 'active',
            'avatar' => $data['avatar'] ?? null,
        ]);

        if (!empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        $this->clearCache();

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);
        
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => $data['status'] ?? $user->status,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (isset($data['avatar'])) {
            $updateData['avatar'] = $data['avatar'];
        }

        $user->update($updateData);

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        $this->clearCache();

        return $user;
    }

    public function delete(int $id): bool
    {
        $user = $this->findById($id);
        $this->clearCache();
        return $user->delete();
    }

    public function restore(int $id): bool
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->clearCache();
        return $user->restore();
    }

    public function bulkDelete(array $ids): int
    {
        $count = User::whereIn('id', $ids)->delete();
        $this->clearCache();
        return $count;
    }

    public function syncRoles(int $userId, array $roleIds): void
    {
        $user = $this->findById($userId);
        $user->roles()->sync($roleIds);
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        // Tagged cache is only supported by redis/memcached
        // For shared hosting, we might just use standard cache or skip tagging
        // But the requirement specifically asked for tags ['users']
        try {
            Cache::tags(['users'])->flush();
        } catch (\Throwable $e) {
            Cache::forget('users.list'); // Fallback
        }
    }
}
