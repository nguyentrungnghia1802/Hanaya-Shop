# 📸 Hướng dẫn Ảnh minh họa cho README.md

## 📂 Cấu trúc thư mục ảnh
```
.github/images/
├── all/                     # Ảnh dùng chung cho cả 3 ngôn ngữ
│   ├── trash1.png          # Ảnh thực tế về vấn đề hoa bị lãng phí
│   ├── performance.png     # Screenshot PageSpeed Insights  
│   └── performance2.png    # Screenshot WebPageTest
├── vi/                     # Ảnh cho phiên bản tiếng Việt
│   ├── hero-banner.png     # Banner chính (tiếng Việt)
│   ├── customer-features.png   # Screenshot tính năng khách hàng
│   ├── customer-features2.png  # Screenshot bổ sung 1
│   ├── customer-features3.png  # Screenshot bổ sung 2
│   ├── admin-dashboard.png     # Dashboard quản trị
│   └── order.png               # Quản lý đơn hàng
├── en/                     # Ảnh cho phiên bản tiếng Anh
│   ├── hero-banner.png     # Banner chính (tiếng Anh)
│   ├── customer-features.png   # Screenshot tính năng khách hàng
│   ├── customer-features2.png  # Screenshot bổ sung 1
│   ├── customer-features3.png  # Screenshot bổ sung 2
│   ├── admin-dashboard.png     # Dashboard quản trị
│   └── order.png               # Quản lý đơn hàng
└── jp/                     # Ảnh cho phiên bản tiếng Nhật
    ├── hero-banner.png     # Banner chính (tiếng Nhật)
    ├── customer-features.png   # Screenshot tính năng khách hàng
    ├── customer-features2.png  # Screenshot bổ sung 1
    ├── customer-features3.png  # Screenshot bổ sung 2
    ├── admin-dashboard.png     # Dashboard quản trị
    └── order.png               # Quản lý đơn hàng
```n Ảnh minh họa cho README.md

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

### 1. **hero-banner.png** - Banner chính (theo ngôn ngữ)
- **Nội dung**: Screenshot homepage với ngôn ngữ tương ứng
- **Kích thước**: 1200x600px (tỷ lệ 2:1)  
- **Định dạng**: PNG để giữ chất lượng text
- **Mô tả**: Thể hiện giao diện chính với ngôn ngữ phù hợp
- **Vị trí**: `.github/images/vi/`, `.github/images/en/`, `.github/images/jp/`

### 2. **trash1.png** - Vấn đề hoa bị lãng phí
- **Nội dung**: Ảnh thực tế về hoa bị vứt bỏ/lãng phí  
- **Kích thước**: 800x500px
- **Định dạng**: PNG
- **Mô tả**: Thể hiện vấn đề thực tế mà dự án muốn giải quyết
- **Vị trí**: `.github/images/all/` (dùng chung)

### 3. **customer-features.png, customer-features2.png, customer-features3.png** - Tính năng khách hàng
- **Nội dung**: Screenshots UI khách hàng với ngôn ngữ tương ứng
- **Kích thước**: 
  - `customer-features.png`: 700x900px (height="700")
  - `customer-features2.png`, `customer-features3.png`: 400x300px (height="300")
- **Định dạng**: PNG
- **Mô tả**: Thể hiện trang sản phẩm, giỏ hàng, checkout theo ngôn ngữ
- **Vị trí**: Theo từng thư mục ngôn ngữ

### 4. **admin-dashboard.png** - Dashboard quản trị
- **Nội dung**: Screenshot trang quản trị với biểu đồ, thống kê
- **Kích thước**: 850x500px (width="850")
- **Định dạng**: PNG
- **Mô tả**: Dashboard với charts doanh thu, sản phẩm bán chạy
- **Vị trí**: Theo từng thư mục ngôn ngữ

### 5. **order.png** - Quản lý đơn hàng
- **Nội dung**: Screenshot trang quản lý đơn hàng
- **Kích thước**: 850x500px (width="850")
- **Định dạng**: PNG
- **Mô tả**: Bảng danh sách đơn hàng, trạng thái, actions
- **Vị trí**: Theo từng thư mục ngôn ngữ

### 6. **performance.png** - PageSpeed Insights
- **Nội dung**: Screenshot PageSpeed Insights của website
- **Kích thước**: 700x400px (width="700")
- **Định dạng**: PNG
- **Mô tả**: Điểm số performance, accessibility, SEO
- **Vị trí**: `.github/images/all/` (dùng chung)

### 7. **performance2.png** - WebPageTest
- **Nội dung**: Screenshot WebPageTest results
- **Kích thước**: 700x400px (width="700")
- **Định dạng**: PNG
- **Mô tả**: Load time, first paint, fully loaded metrics
- **Vị trí**: `.github/images/all/` (dùng chung)

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
- [ ] Tên file đúng format (lowercase, dấu gạch ngang, đuôi .png)
- [ ] Đường dẫn ảnh trong README.md chính xác (`.github/images/...`)
- [ ] Ảnh hiển thị tốt trên cả desktop và mobile
- [ ] Alt text mô tả rõ ràng cho accessibility
- [ ] Ảnh có ngôn ngữ phù hợp (vi/en/jp) hoặc language-neutral (all/)
- [ ] Screenshots có UI/UX thống nhất và professional

## 🗂️ Thư mục cần tạo

Trước khi upload ảnh, tạo các thư mục sau:
```bash
mkdir -p .github/images/all
mkdir -p .github/images/vi  
mkdir -p .github/images/en
mkdir -p .github/images/jp
```

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
