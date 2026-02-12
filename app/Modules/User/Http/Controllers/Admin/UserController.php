<?php

namespace App\Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\User\Http\Requests\StoreUserRequest;
use App\Modules\User\Http\Requests\UpdateUserRequest;
use App\Modules\User\Models\Role;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', User::class);

        return Inertia::render('Admin/Users/Index', [
            'users' => $this->userRepository->allPaginated(15, $request->all(['search', 'role', 'status'])),
            'roles' => Role::all(['id', 'name', 'slug']),
            'filters' => $request->all(['search', 'role', 'status']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/Users/Form', [
            'roles' => Role::all(['id', 'name', 'slug']),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validated();
        
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $this->userRepository->create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): Response
    {
        $this->authorize('update', $user);

        return Inertia::render('Admin/Users/Form', [
            'user' => $user->load('roles'),
            'roles' => Role::all(['id', 'name', 'slug']),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $this->userRepository->update($user->id, $data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $this->userRepository->delete($user->id);

        return back()->with('success', 'User deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $this->authorize('deleteAny', User::class);

        $ids = $request->input('ids', []);
        
        if (in_array(auth()->id(), $ids)) {
            return back()->with('error', 'You cannot delete yourself in bulk operations.');
        }

        $this->userRepository->bulkDelete($ids);

        return back()->with('success', 'Selected users deleted successfully.');
    }

    public function restore(int $id)
    {
        $this->authorize('restore', User::class);
        $this->userRepository->restore($id);
        return back()->with('success', 'User restored successfully.');
    }
}
