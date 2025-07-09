<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order\Order;
use App\Models\Cart\Cart;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'users' => 'required|array|min:1',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.password' => 'required|string|min:6',
        ]);
        foreach ($request->users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
                'role' => 'user',
            ]);
        }
        return redirect()->route('admin.user')->with('success', 'Tạo tài khoản thành công!');
    }

    public function edit($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return redirect()->route('admin.user')->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) $ids = [$ids];
        User::where('role', 'user')->whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $orders = $user->order()->get();
        $carts = $user->cart()->with('product')->get();
        return view('admin.users.show', compact('user', 'orders', 'carts'));
    }
}
