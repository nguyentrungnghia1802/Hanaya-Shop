# 🌸 Hanaya Shop - Laravel Project Quick Guide

---

## 🚀 Khởi động dự án

### 1. Tạo project mới
```bash
composer create-project --prefer-dist laravel/laravel hanaya_shop
```

### 2. Chạy Laravel server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

> **Lưu ý:** Nếu app.css không load, hãy mở thêm một terminal và chạy:
```bash
npm run dev
```

---

## 📝 Các lệnh phát triển

- **Tạo file view mới:**
  ```bash
  php artisan make:view layouts.slider
  ```
- **Reset migrations:**
  ```bash
  php artisan migrate:reset
  ```
- **Chạy seeder:**
  ```bash
  php artisan db:seed
  ```
- **Import dữ liệu mẫu:**
  ```bash
  mysql -u root -p hanaya_shop_demo < .\database\sql\sample_data.sql
  ```

---

## 🔄 Quy trình phát triển

- Phát triển theo mô hình **Incremental Development + Agile**

---

## ✨ Sử dụng TinyMCE API cho Content Posts

1. Đăng nhập: [TinyMCE Domains Portal](https://www.tiny.cloud/my-account/domains/)
2. Thêm domain của ứng dụng (ví dụ: `localhost`, `127.0.0.1`, hoặc domain thật).
3. Quay lại và sử dụng API key trong cấu hình TinyMCE.

> Nếu gặp lỗi domain, hãy kiểm tra lại domain đã được thêm vào portal của TinyMCE.

---

## 📚 Tài liệu tham khảo
- [Laravel Docs](https://laravel.com/docs)
- [TinyMCE Docs](https://www.tiny.cloud/docs/)

---

> **Hanaya Shop** - Flower & Product Blog Platform

---

**Made with ❤️ by Hanaya Team**
