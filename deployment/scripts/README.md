# Scripts Deployment - Hanaya Shop

Tất cả scripts deployment đã được gộp vào 1 thư mục duy nhất để dễ quản lý.

## 🗂️ Phân Loại Scripts

### 📦 **CI/CD & Production Deploy**
| Script | Mục đích | Khi nào dùng |
|--------|----------|--------------|
| `deploy-production.sh` | Deploy production đầy đủ | Lần đầu hoặc deploy major |
| `deploy.sh` | Deploy script chạy trên server | Được gọi từ CI/CD |
| `verify-deployment.sh` | Kiểm tra health sau deploy | Sau mỗi deployment |

### 🔄 **Update & Maintenance**
| Script | Mục đích | Khi nào dùng |
|--------|----------|--------------|
| `update-image.sh` | Update image với backup | Cập nhật code thường xuyên |
| `update.sh` | Update nhanh trên server | Cập nhật đơn giản |
| `clear-cache.sh` | Xóa cache Laravel | Sau khi update config/code |

### ⚙️ **Setup & Management**
| Script | Mục đích | Khi nào dùng |
|--------|----------|--------------|
| `setup-server.sh` | Cài đặt server từ đầu | Lần đầu setup server |
| `auto-backup.sh` | Backup tự động hàng ngày | Chạy tự động hoặc thủ công |
| `monitor.sh` | Monitor hệ thống realtime | Kiểm tra trạng thái |

## 🚀 Quy Trình Deployment Khuyến Nghị

### 1️⃣ **Lần Đầu Setup**
```bash
./setup-server.sh           # Setup server Ubuntu
./deploy-production.sh      # Deploy đầy đủ
./verify-deployment.sh      # Kiểm tra health
```

### 2️⃣ **Cập Nhật Thường Xuyên**
```bash
./update-image.sh           # Update với backup tự động
./verify-deployment.sh      # Verify sau update
```

### 3️⃣ **Maintenance**
```bash
./monitor.sh               # Check system status
./auto-backup.sh           # Manual backup
./clear-cache.sh           # Clear cache if needed
```

## 🎯 **Usage Examples**

```bash
# Setup server lần đầu
chmod +x *.sh
./setup-server.sh

# Deploy production
./deploy-production.sh

# Monitor hệ thống
./monitor.sh

# Backup manual
./auto-backup.sh

# Update code mới
./update-image.sh
```

## 📋 **Scripts Removed**
- ❌ `*.bat` files (Windows scripts)
- ❌ Duplicate deployment scripts
- ❌ Unused utility scripts
- ✅ Chỉ giữ lại scripts essential và không trùng lặp