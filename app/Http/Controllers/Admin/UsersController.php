<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Models\Order\Order;
use App\Models\Cart\Cart;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Hiển thị danh sách tất cả người dùng có role = 'user'.
     * Dữ liệu được cache trong 10 phút để tăng hiệu năng.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Không dùng cache cho phân trang
        $users = User::where('id', '!=', Auth::id())->paginate(20); // 20 user mỗi trang, loại bỏ chính mình
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
            'users.*.role' => 'required|in:user,admin',
        ]);

        // Lặp qua từng người dùng và tạo tài khoản
        foreach ($request->input('users') as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
                'role' => $userData['role'],
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
        if ($id == Auth::id()) {
            abort(403, 'Bạn không thể sửa chính mình.');
        }
        $user = User::findOrFail($id);
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
        if ($id == Auth::id()) {
            abort(403, 'Bạn không thể cập nhật chính mình.');
        }
        $user = User::findOrFail($id);

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->getKey(),
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:6',
        ]);

        // Cập nhật thông tin người dùng
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        // Nếu có nhập mật khẩu mới thì cập nhật
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
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
        if (!is_array($ids)) $ids = [$ids];
        // Loại bỏ id của admin đang đăng nhập khỏi danh sách xóa
        $ids = array_diff($ids, [Auth::id()]);
        User::whereIn('id', $ids)->delete();

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
        $user = User::findOrFail($id);

        // Lấy danh sách đơn hàng
        $orders = $user->order()->get();

        // Lấy danh sách giỏ hàng kèm thông tin sản phẩm
        $carts = $user->cart()->with('product')->get();

        return view('admin.users.show', compact('user', 'orders', 'carts'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query', '');

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->get();

        $html = '';
        if ($users->count() > 0) {
            foreach ($users as $user) {
                $html .= '<tr>
                    <td class="px-4 py-2 border-b"><input type="checkbox" class="check-user" value="' . $user->getKey() . '"></td>
                    <td class="px-4 py-2 border-b">' . $user->getKey() . '</td>
                    <td class="px-4 py-2 border-b">' . e($user->name) . '</td>
                    <td class="px-4 py-2 border-b">' . e($user->email) . '</td>
                    <td class="px-4 py-2 border-b">' . e($user->role) . '</td>
                    <td class="px-4 py-2 border-b">
                        <div class="flex flex-wrap gap-2">
                            <a href="' . route('admin.user.edit', $user->getKey()) . '" class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition">Edit</a>
                            <button type="button" class="px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete" data-id="' . $user->getKey() . '">Delete</button>
                            <a href="' . route('admin.user.show', $user->getKey()) . '" class="px-3 py-1 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">View Details</a>
                        </div>
                    </td>
                </tr>';
            }
        } else {
            $html = '<tr><td colspan="6" class="text-center py-4 text-gray-500">No users found.</td></tr>';
        }

        return response()->json(['html' => $html]);
    }

}
