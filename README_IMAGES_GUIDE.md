# 📸 Hướng dẫn Ảnh minh họa cho README.md

## 📂 Cấu trúc thư mục ảnh
```
.github/images/
├── hero-banner.png          # Ảnh banner chính
├── architecture-overview.png # Sơ đồ kiến trúc hệ thống  
├── customer-features.jpg    # Screenshot tính năng khách hàng
├── customer-journey.png     # Sơ đồ quy trình khách hàng
├── admin-dashboard.jpg      # Screenshot dashboard quản trị
├── tech-stack.png           # Sơ đồ công nghệ sử dụng
└── performance-metrics.png  # Biểu đồ hiệu suất hệ thống
```

## 🎨 Yêu cầu chi tiết từng ảnh

### 1. **hero-banner.jpg** - Banner chính
- **Nội dung**: Ảnh tổng quan về website Hanaya Shop (homepage hoặc mockup đẹp)
- **Kích thước**: 1200x600px (tỷ lệ 2:1)
- **Định dạng**: JPG, chất lượng cao (80-90%)
- **Mô tả**: Thể hiện giao diện chính của website, có logo, menu, sản phẩm nổi bật

### 2. **architecture-overview.png** - Sơ đồ kiến trúc
- **Nội dung**: Sơ đồ kiến trúc tổng quan của hệ thống (Frontend, Backend, Database, Cache)
- **Kích thước**: 800x500px 
- **Định dạng**: PNG (để giữ độ nét của text)
- **Mô tả**: Bao gồm các thành phần: Laravel, MySQL, Redis, Docker, Vite

### 3. **customer-features.jpg** - Tính năng khách hàng
- **Nội dung**: Screenshot giao diện khách hàng (trang sản phẩm, giỏ hàng, hoặc checkout)
- **Kích thước**: 700x400px
- **Định dạng**: JPG
- **Mô tả**: Thể hiện UI/UX cho khách hàng, danh sách sản phẩm, filter, cart

### 4. **customer-journey.png** - Quy trình khách hàng
- **Nội dung**: Flowchart mô tả hành trình mua hàng (Xem sản phẩm → Thêm vào giỏ → Thanh toán → Hoàn thành)
- **Kích thước**: 600x300px
- **Định dạng**: PNG
- **Mô tả**: Sử dụng icon và arrow để thể hiện các bước mua hàng

### 5. **admin-dashboard.jpg** - Dashboard quản trị
- **Nội dung**: Screenshot trang quản trị (dashboard với charts, thống kê, bảng dữ liệu)
- **Kích thước**: 700x400px  
- **Định dạng**: JPG
- **Mô tả**: Hiển thị biểu đồ doanh thu, quản lý đơn hàng, sản phẩm

### 6. **tech-stack.png** - Công nghệ sử dụng
- **Nội dung**: Sơ đồ/infographic các công nghệ: PHP, Laravel, MySQL, Redis, Docker, Tailwind
- **Kích thước**: 800x400px
- **Định dạng**: PNG
- **Mô tả**: Logo của các công nghệ được sắp xếp theo layers (Frontend, Backend, Database, DevOps)

### 7. **performance-metrics.png** - Chỉ số hiệu suất
- **Nội dung**: Biểu đồ thể hiện performance (Page load time, Response time, Uptime, etc.)
- **Kích thước**: 650x350px
- **Định dạng**: PNG  
- **Mô tả**: Charts/graphs thể hiện các metric quan trọng của hệ thống

## 🛠️ Công cụ tạo ảnh khuyến nghị

### Screenshots thực tế:
- Sử dụng browser developer tools để capture responsive design
- Tool: **Awesome Screenshot**, **Lightshot**, hoặc built-in screenshot

### Sơ đồ/Infographic:
- **Figma** (miễn phí): Tạo sơ đồ kiến trúc, flowchart
- **Draw.io** (miễn phí): Sơ đồ kỹ thuật
- **Canva** (miễn phí): Infographic, banner đẹp

### Biểu đồ/Charts:
- **Chart.js** + Canvas export: Tạo charts thực tế từ data
- **Google Charts** + Screenshot
- **Figma/Canva**: Mockup charts đẹp mắt

## 📋 Checklist trước khi commit

- [ ] Tất cả ảnh đã được tối ưu kích thước (< 500KB mỗi file)
- [ ] Tên file đúng format (lowercase, dấu gạch ngang)
- [ ] Đường dẫn ảnh trong README.md chính xác
- [ ] Ảnh hiển thị tốt trên cả desktop và mobile
- [ ] Alt text mô tả rõ ràng cho accessibility

## 🚀 Lệnh tối ưu ảnh (optional)

Nếu có ImageMagick hoặc tools tương tự:
```bash
# Tối ưu kích thước và chất lượng
convert input.jpg -resize 1200x600> -strip -quality 85 output.jpg

# Tối ưu PNG
optipng -o2 input.png
```

## 📝 Ghi chú

- Ưu tiên sử dụng screenshot thực tế từ ứng dụng đã deploy
- Nếu chưa có UI hoàn chỉnh, có thể tạo mockup/wireframe tạm
- Đảm bảo ảnh phản ánh đúng tính năng được mô tả
- Cân nhắc dark/light theme consistency
- Thêm watermark hoặc branding nếu cần thiết
