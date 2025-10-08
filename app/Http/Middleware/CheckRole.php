<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Lấy vai trò người dùng hiện tại
        $userRole = Auth::user()->role;

        // Nếu vai trò nằm trong danh sách cho phép → tiếp tục
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Nếu không đúng role, báo lỗi 403
        abort(403, 'Bạn không có quyền truy cập.');
    }
}
