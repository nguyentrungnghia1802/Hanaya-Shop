<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Models\Order\Order;
use App\Models\Cart\Cart;

class UsersController extends Controller
{
    /**
     * Hiển thị danh sách tất cả người dùng có role = 'user'.
     * Dữ liệu được cache trong 60 phút để tăng hiệu năng.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = Cache::remember('admin_users_all', 3600, function () {
            return User::where('role', 'user')->get();
        });
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form tạo mới người dùng.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Xử lý tạo mới nhiều người dùng cùng lúc.
     * Áp dụng validation và mã hóa mật khẩu.
     * Xóa cache sau khi thêm mới.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Xác thực đầu vào mảng user
        $request->validate([
            'users' => 'required|array|min:1',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.password' => 'required|string|min:6',
        ]);

        // Lặp qua từng người dùng và tạo tài khoản
        foreach ($request->users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
                'role' => 'user',
            ]);
        }

        // Xóa cache để làm mới dữ liệu
        Cache::forget('admin_users_all');

        return redirect()->route('admin.user')->with('success', 'Tạo tài khoản thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa người dùng.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     * Hỗ trợ cập nhật có hoặc không đổi mật khẩu.
     * Xóa cache sau khi cập nhật.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        // Cập nhật thông tin người dùng
        $user->name = $request->name;
        $user->email = $request->email;

        // Nếu có nhập mật khẩu mới thì cập nhật
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Làm mới cache
        Cache::forget('admin_users_all');

        return redirect()->route('admin.user')->with('success', 'Cập nhật tài khoản thành công!');
    }

    /**
     * Xóa một hoặc nhiều người dùng theo ID.
     * Hỗ trợ cả request thông thường và Ajax.
     * Xóa cache sau khi thao tác.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);

        // Đảm bảo $ids luôn là mảng
        if (!is_array($ids)) $ids = [$ids];

        // Xóa người dùng với role = 'user' theo danh sách ID
        User::where('role', 'user')->whereIn('id', $ids)->delete();

        // Làm mới cache
        Cache::forget('admin_users_all');

        // Trả về JSON nếu là Ajax
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.user')->with('success', 'Xóa tài khoản thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết người dùng kèm đơn hàng và giỏ hàng.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        // Lấy danh sách đơn hàng
        $orders = $user->order()->get();

        // Lấy danh sách giỏ hàng kèm thông tin sản phẩm
        $carts = $user->cart()->with('product')->get();

        return view('admin.users.show', compact('user', 'orders', 'carts'));
    }

    /**
     * Search for users by name or email.
     */
    public function search(Request $request)
    {
        $query = $request->input('query', '');

        $users = User::where('role', 'user')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->get();

        // Trả về HTML table rows để render lại tbody
        $html = view('admin.users.partials.table_rows', compact('users'))->render();
        return response()->json(['html' => $html]);
    }

}
