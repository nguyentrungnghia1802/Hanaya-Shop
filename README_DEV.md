# 🌸 Hanaya Shop - Development Documentation

> **Tài liệu phát triển và cập nhật dự án Hanaya Shop**
> 
> *Cập nhật lần cuối: 22/07/2025*

---

## 📋 Mục lục

- [🚀 Hướng dẫn phát triển](#-hướng-dẫn-phát-triển)
- [📈 Lịch sử cập nhật](#-lịch-sử-cập-nhật)
- [🔧 Các cải tiến đã thực hiện](#-các-cải-tiến-đã-thực-hiện)
- [📝 Lệnh phát triển](#-lệnh-phát-triển)
- [🗄️ Cấu trúc Database](#️-cấu-trúc-database)
- [🧪 Testing & Deployment](#-testing--deployment)

---

## 🚀 Hướng dẫn phát triển

### Khởi động dự án

#### 1. Tạo project mới
```bash
composer create-project --prefer-dist laravel/laravel hanaya_shop
```

#### 2. Chạy Laravel server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

> **Truy cập ứng dụng tại:**
> 
> - http://127.0.0.1:8000
> - http://localhost:8000
> - http://<IPv4>:8000  (VD: http://192.168.1.101:8000)

> **Lưu ý:** Nếu app.css không load, hãy mở thêm một terminal và chạy:
```bash
npm run dev
```

#### 3. Quy trình phát triển
- Phát triển theo mô hình **Incremental Development + Agile**

## 🛠️ Yêu cầu phần mềm & thư viện ngoài Composer

Để chạy đầy đủ project Hanaya Shop, ngoài các package PHP/NPM, bạn cần cài thêm:

### 1. Redis Server cho Windows
- **Redis-x64-5.0.14.1**  
  Tải tại: [https://github.com/tporadowski/redis/releases](https://github.com/tporadowski/redis/releases)
- Giải nén và chạy `redis-server.exe` trước khi khởi động Laravel.

### 2. PHP Redis Extension
- **php_redis.dll**  
  Tải đúng phiên bản PHP tại: [https://pecl.php.net/package/redis](https://pecl.php.net/package/redis)
- Copy vào thư mục `C:\xampp\php\ext\`
- Thêm dòng `extension=php_redis.dll` vào file `php.ini`
- Khởi động lại Apache

### 3. Node.js & NPM
- Tải tại: [https://nodejs.org/](https://nodejs.org/)
- Đảm bảo đã cài Node.js để chạy `npm install` và `npm run dev`

### 4. MySQL Server
- Đã cài đặt MySQL (XAMPP hoặc MariaDB)
- Đảm bảo cấu hình kết nối đúng trong `.env`

### 5. Mailpit (Local SMTP Testing)
- Tải tại: [https://github.com/axllent/mailpit/releases](https://github.com/axllent/mailpit/releases)
- Chạy `mailpit` để test email gửi từ Laravel

---

> **Lưu ý:** Nếu thiếu Redis hoặc extension, các tính năng cache, queue, session sẽ không hoạt động!

---

## 📈 Lịch sử cập nhật

### Phase 1: Core Features (Hoàn thành - Tháng 4/2025)

#### 1. 🏠 Dashboard User đã được thiết kế lại
- **Banner slider tự động** với thông tin cửa hàng
- **Section bài viết mới nhất** từ blog
- **Sản phẩm theo danh mục**: Hiển thị sản phẩm mới nhất của từng loại
- **Sản phẩm bán chạy nhất**: Top sản phẩm được yêu thích
- **Section thông tin cửa hàng** với các ưu điểm

#### 2. 🛍️ Hệ thống sản phẩm với navigation category
- **Category Navigation Component**: 4 danh mục chính
  - Hoa Xà Phòng (soap-flower)
  - Hoa Giấy (paper-flower) 
  - Hoa Tươi (fresh-flowers)
  - Quà Lưu Niệm (souvenir)
- **Lọc sản phẩm theo category_name** qua URL parameter
- **Hiển thị động tên page title** theo danh mục
- **Tích hợp search và filter** với category navigation

#### 3. 📊 Admin Dashboard với thống kê chi tiết
- **Biểu đồ thống kê doanh thu** theo tháng (Chart.js)
- **Thống kê đơn hàng** theo trạng thái
- **Top sản phẩm bán chạy** với số lượng đã bán
- **Thống kê user** và bài viết
- **Thống kê sản phẩm** theo danh mục
- **Giao diện responsive** với Tailwind CSS

#### 4. 🤖 Chatbot thông minh
- **Xử lý ngôn ngữ tự nhiên** tiếng Việt
- **Tìm kiếm sản phẩm** theo từ khóa
- **Thông tin bài viết mới nhất** với tóm tắt
- **Hỗ trợ danh mục sản phẩm** với link trực tiếp
- **Thông tin đơn hàng** và cửa hàng
- **Giao diện chat responsive** với animation

### Phase 2: Performance & Optimization (Hoàn thành - Tháng 6/2025)

#### 🔧 Khắc phục lỗi cơ sở dữ liệu
- ✅ Thêm trường `discount_percent` và `view_count` vào bảng products
- ✅ Xóa trường discount không hợp lệ từ bảng orders
- ✅ Cập nhật model Product với các accessor cho giá sau giảm giá
- ✅ Sửa lỗi component `category-navigation` không tìm thấy

#### 📱 Responsive Design
- ✅ Tối ưu hóa giao diện cho mobile, tablet và desktop
- ✅ Grid sản phẩm responsive (1-5 cột tùy theo màn hình)
- ✅ Form tìm kiếm và bộ lọc thân thiện mobile
- ✅ Nút hành động và navigation responsive
- ✅ Typography và spacing tối ưu cho mọi thiết bị

#### ⚡ Tối ưu hóa hiệu suất
- ✅ Implement cache cho ProductController (15 phút)
- ✅ Cache cho DashboardController (30 phút)
- ✅ Tăng view_count khi xem chi tiết sản phẩm
- ✅ Thêm database indexes cho các truy vấn thường dùng
- ✅ Lazy loading cho hình ảnh
- ✅ Optimize CSS với Tailwind utilities

#### 🛠️ Cải tiến kỹ thuật
- ✅ Thêm CacheService để quản lý cache
- ✅ Command `app:clear-cache` để xóa cache ứng dụng
- ✅ Scripts cleanup cho Windows và Linux
- ✅ Cải thiện routes với namespace `user.products`
- ✅ Backward compatibility với routes cũ

---

## 📝 Lệnh phát triển

### Cache Management
```bash
# Xóa cache ứng dụng
php artisan app:clear-cache

# Xóa tất cả cache (production)
php artisan app:clear-cache --all

# Xóa cache Laravel cơ bản
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Cleanup Scripts
```bash
# Windows
./cleanup.bat

# Linux/Mac
chmod +x cleanup.sh
./cleanup.sh
```

### Development Commands
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

### Database
```bash
# Chạy migrations mới
php artisan migrate

# Rollback migration (nếu cần)
php artisan migrate:rollback
```

---

## 🗄️ Cấu trúc Database

### Cấu trúc Components mới

#### Home Components
```
resources/views/components/home/
├── banner-slider.blade.php      # Banner tự động với Swiper.js
├── latest-posts.blade.php       # Hiển thị bài viết mới nhất
└── categories.blade.php         # Grid danh mục sản phẩm
```

#### Product Components  
```
resources/views/components/
├── category-navigation.blade.php  # Navigation 4 danh mục chính
└── category-products.blade.php    # Hiển thị sản phẩm theo category
```

#### Chatbot Component
```
resources/views/components/
└── chatbot.blade.php             # UI chatbot với animation
```

### Routes đã cập nhật

#### User Routes
```php
// Sản phẩm với category navigation
Route::get('/products', [ProductController::class, 'index'])->name('user.products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('user.products.show');

// Chatbot
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
```

#### Admin Routes (không thay đổi)
```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});
```

### Các tính năng Category Navigation

#### URL Parameters hỗ trợ:
- `category_name`: soap-flower, paper-flower, fresh-flowers, souvenir  
- `category`: ID danh mục từ database
- `sort`: latest, asc, desc, sale, views, bestseller
- `q`: từ khóa tìm kiếm

#### Ví dụ URLs:
```
/products?category_name=soap-flower
/products?category_name=paper-flower&sort=bestseller  
/products?q=hoa&category_name=fresh-flowers
```

### Cấu trúc cache

#### Product Cache
- `products_index_{hash}` - Cache danh sách sản phẩm (15 phút)
- `product_detail_{id}` - Cache chi tiết sản phẩm (30 phút)
- `related_products_{id}` - Cache sản phẩm liên quan (30 phút)

#### Dashboard Cache
- `dashboard_stats` - Thống kê dashboard (30 phút)
- `dashboard_recent_products` - Sản phẩm mới (30 phút)
- `dashboard_categories` - Danh mục (30 phút)
- `dashboard_recent_orders` - Đơn hàng mới (30 phút)

### Database Indexes
- `category_id` - Lọc theo danh mục
- `price` - Sắp xếp theo giá
- `discount_percent` - Sắp xếp theo khuyến mãi
- `view_count` - Sắp xếp theo lượt xem
- `created_at` - Sắp xếp theo ngày tạo
- `(category_id, price)` - Composite index
- `(discount_percent, price)` - Sale products
- Full-text search on `(name, descriptions)`

### Routes mới
- `GET /products` - `user.products.index`
- `GET /products/{id}` - `user.products.show`

Routes cũ vẫn hoạt động:
- `GET /product` - `product.index`
- `GET /product/{id}` - `product.show`

### CSS Classes mới
```css
.line-clamp-2    /* Giới hạn 2 dòng text */
.card-product    /* Card sản phẩm với hover effects */
.btn-primary     /* Button chính */
.input-field     /* Input field styled */
```

### Responsive Breakpoints
- `xs: 475px` - Extra small devices
- `sm: 640px` - Small devices
- `md: 768px` - Medium devices  
- `lg: 1024px` - Large devices
- `xl: 1280px` - Extra large devices

---

## 📚 JavaScript Libraries đã tích hợp

### Frontend
- **Swiper.js**: Banner slider tự động
- **Chart.js**: Biểu đồ thống kê admin
- **SweetAlert2**: Thông báo đẹp
- **Tailwind CSS**: Responsive design

### Backend  
- **Laravel Eloquent**: ORM relationships
- **Laravel Collections**: Data processing
- **Laravel Pagination**: Phân trang sản phẩm

---

## 🗄️ Database Structure Updates

### Orders Table
```sql
-- Thêm trường discount thay thế coupon system
ALTER TABLE orders ADD COLUMN discount DECIMAL(10,2) DEFAULT 0;

-- Cập nhật enum status
ALTER TABLE orders MODIFY COLUMN status ENUM('pending','confirmed','processing','shipping','delivered','completed','cancelled','refunded');
```

### Removed Tables
- `coupons` table đã xóa
- `applied_coupons` table đã xóa  

### 🗄️ Cơ sở dữ liệu đã được tối ưu
- **Order model**: 
  - Thêm trường `discount` thay thế coupon system
  - Xóa relationship với applied_coupons
  - Cập nhật các status mới
- **Xóa Coupon và Applied Coupon models** không sử dụng
- **Migration được cập nhật** phù hợp với cấu trúc mới

---

## 🧪 Testing & Deployment

### Monitoring & Performance
- View count tracking cho sản phẩm
- Cache hit/miss monitoring
- Database query optimization
- Image lazy loading
- CSS/JS minification

### Deployment Notes
Để deploy lên production:
1. Chạy `npm run build` để build assets
2. Chạy `php artisan config:cache` để cache config
3. Chạy `php artisan route:cache` để cache routes
4. Chạy `php artisan view:cache` để cache views
5. Đảm bảo Redis/Memcached cho cache hiệu quả

#### Cần chạy sau khi deploy:
```bash
# Migrate database changes
php artisan migrate

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimize
php artisan optimize
```

#### Dependencies cần cài đặt:
```bash
# Không cần cài thêm package PHP nào

# Frontend assets đã có sẵn via CDN:
# - Swiper.js
# - Chart.js  
# - SweetAlert2
```

### Testing Checklist

#### ✅ Đã test các tính năng:
- [x] Dashboard user hiển thị banner, posts, products
- [x] Category navigation chuyển trang đúng
- [x] Product filtering theo category_name  
- [x] Admin dashboard charts render
- [x] Chatbot response đúng các câu hỏi
- [x] Database migration chạy thành công

#### 🔄 Cần test thêm:
- [ ] Performance với dữ liệu lớn
- [ ] Mobile responsive trên các thiết bị
- [ ] SEO optimization
- [ ] Cart functionality integration

### Refresh Backend
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan clear-compiled
composer dump-autoload
```

---

## ✨ TinyMCE API Integration

### Sử dụng TinyMCE API cho Content Posts

1. Đăng nhập: [TinyMCE Domains Portal](https://www.tiny.cloud/my-account/domains/)
2. Thêm domain của ứng dụng (ví dụ: `localhost`, `127.0.0.1`, hoặc domain thật).
3. Quay lại và sử dụng API key trong cấu hình TinyMCE.

> Nếu gặp lỗi domain, hãy kiểm tra lại domain đã được thêm vào portal của TinyMCE.

---

## 🆘 Troubleshooting

### Liên hệ Support

Nếu có vấn đề khi triển khai, hãy kiểm tra:
1. **Database connection** đã đúng
2. **File permissions** cho storage và cache  
3. **Environment variables** đã setup
4. **Web server rewrite rules** cho pretty URLs

---

## 📚 Tài liệu tham khảo
- [Laravel Docs](https://laravel.com/docs)
- [TinyMCE Docs](https://www.tiny.cloud/docs/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Chart.js](https://www.chartjs.org/docs/)

---

> **Hanaya Shop** - Flower & Product Blog Platform
> 
> **Made with ❤️ by Hanaya Team**
> 
> *Cập nhật lần cuối: 22/07/2025 - Phase 2 hoàn thành*
