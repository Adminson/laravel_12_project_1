<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserAdminController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function list(Request $request)
    {
        $query = User::query()
            ->select(['id', 'name', 'email', 'user_type', 'account_lock', 'mobile_no', 'created_at']);

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', fn($u) => $u->created_at?->format('H:i d-M-Y'))
            ->editColumn('user_type', fn($u) => ucfirst($u->user_type))
            ->editColumn('account_lock', fn($u) => $u->account_lock
                ? '<span class="badge bg-danger">Locked</span>'
                : '<span class="badge bg-success">Active</span>')
            ->addColumn('action', function ($user) {
                return '
                    <button type="button"
                        class="btn btn-sm btn-primary btn-edit-user"
                        data-id="' . $user->id . '">
                        <i class="ti tabler-pencil"></i> Edit
                    </button>';
            })
            ->rawColumns(['account_lock', 'action'])
            ->toJson();
    }

    public function show(User $user)
    {
        return response()->json([
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'user_type'    => $user->user_type,
            'account_lock' => $user->account_lock ? 1 : 0,
            'mobile_no'    => $user->mobile_no,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8'],
            'user_type'    => ['required', Rule::in(['user', 'staff', 'admin'])],
            'account_lock' => ['nullable', 'boolean'],
            'mobile_no'    => ['nullable', 'string', 'max:25'],
        ]);

        User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'user_type'    => $validated['user_type'],
            'account_lock' => $request->boolean('account_lock'),
            'mobile_no'    => $validated['mobile_no'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'User created successfully.']);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'     => ['nullable', 'string', 'min:8'],
            'user_type'    => ['required', Rule::in(['user', 'staff', 'admin'])],
            'account_lock' => ['nullable', 'boolean'],
            'mobile_no'    => ['nullable', 'string', 'max:25'],
        ]);

        $data = [
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'user_type'    => $validated['user_type'],
            'account_lock' => $request->boolean('account_lock'),
            'mobile_no'    => $validated['mobile_no'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }
}
