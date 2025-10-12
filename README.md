# 🌸 Hanaya Shop - Website Bán Hoa Online

**Hanaya Shop** là một ứng dụng web bán hoa online được xây dựng nhằm hỗ trợ người dùng dễ dàng lựa chọn, đặt mua và thanh toán các sản phẩm hoa tươi thông qua giao diện web hiện đại, tiện lợi và tối ưu trải nghiệm người dùng.

---

## 🎯 Mục Tiêu Dự Án

- Xây dựng nền tảng thương mại điện tử đơn giản cho các cửa hàng hoa.
- Quản lý sản phẩm (hoa), giỏ hàng và đơn hàng hiệu quả.
- Tích hợp giao diện quản trị cho admin.
- Triển khai hoàn toàn bằng **Docker**, không cần chỉnh `.env`, giúp dễ dàng cấu hình môi trường.

---

## 🌟 Tính Năng Chính

### 👤 Dành cho Khách hàng
- Xem danh sách sản phẩm hoa, lọc theo loại hoa / dịp / giá.
- Xem chi tiết sản phẩm, hình ảnh, giá cả.
- Thêm sản phẩm vào giỏ hàng và tạo đơn hàng.
- Xem lịch sử mua hàng *(nếu đã đăng ký tài khoản)*.

### 🛠️ Dành cho Quản trị viên (Admin)
- Quản lý danh mục hoa.
- CRUD sản phẩm: thêm, sửa, xóa, bật/tắt hiển thị.
- Quản lý đơn hàng: xác nhận, huỷ, cập nhật trạng thái.
- Quản lý khách hàng.

---

## 🛠️ Công Nghệ Sử Dụng

- **PHP 8.2**, **Laravel 12.2** – Backend API và hệ thống quản lý.
- **MySQL** – Lưu trữ dữ liệu sản phẩm, người dùng, đơn hàng.
- **Blade template** Dùng giao diện server-side
- **Docker Compose** – Triển khai môi trường Laravel + MySQL nhanh chóng.

---

## 🗂️ Cấu Trúc Dự Án

<details>
<summary><strong>📁 hanaya-shop/</strong></summary>



