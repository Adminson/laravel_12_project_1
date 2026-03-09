<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
            ->select([
                'id',
                'name',
                'email',
                'created_at',
            ]);

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($user) {
                return $user->created_at?->format('H:i d-M-Y');
            })
            ->addColumn('action', function ($user) {
                $editUrl = '#'; // replace later with your real edit route
                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
