<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'in:student,admin'],
        ]);

        $search = $validated['search'] ?? null;
        $role = $validated['role'] ?? null;

        return view('admin.users.index', [
            'users' => $this->userService->getAllForAdmin(
                $search,
                $role
            ),
            'statistics' => $this->userService->getStatistics(),
            'search' => $search,
            'role' => $role,
        ]);
    }

    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $this->userService->getForAdminDetails($user),
        ]);
    }
}
