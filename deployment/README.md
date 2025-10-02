# Hanaya Shop - Deployment Guide

🚀 **Hệ thống deployment hoàn chỉnh cho Hanaya Shop trên Ubuntu Server**

## 🏗️ Cấu Trúc Thư Mục

```
deployment/
├── docker-compose.prod.yml          # 🐳 Production Docker Compose
├── scripts/                         # 📜 Scripts chính
│   ├── clear-cache.sh              # 🧹 Xóa cache Laravel
│   ├── deploy-production.sh        # 🚀 Deploy production
│   ├── update-image.sh             # 🔄 Update Docker image
│   ├── verify-deployment.sh        # ✅ Kiểm tra deployment
│   └── README.md                   # 📖 Hướng dẫn scripts
├── server-scripts/                  # 🖥️ Scripts server
│   ├── setup-server.sh             # ⚙️ Setup server ban đầu
│   ├── deploy.sh                   # 📦 Deploy trên server
│   ├── update.sh                   # 🔄 Update trên server
│   ├── auto-backup.sh              # 💾 Backup tự động
│   ├── monitor.sh                  # 📊 Monitor hệ thống
│   └── check-dependencies.sh       # 🔍 Kiểm tra dependencies
├── nginx/                          # 🌐 Nginx configs
├── mysql/                          # 🗄️ MySQL configs
├── php/                            # 🐘 PHP configs
└── supervisor/                     # 👷 Process supervisor
```

## 🚀 Quy Trình Deployment

### 1️⃣ Lần Đầu Deployment
```bash
# Trên server Ubuntu
./server-scripts/setup-server.sh
./scripts/deploy-production.sh
```

### 2️⃣ Cập Nhật Ứng Dụng
```bash
# Cập nhật image với backup tự động
./scripts/update-image.sh
```

### 3️⃣ Kiểm Tra Hệ Thống
```bash
# Verify deployment
./scripts/verify-deployment.sh

# Monitor hệ thống
./server-scripts/monitor.sh
```

### 4️⃣ Bảo Trì
```bash
# Xóa cache
./scripts/clear-cache.sh

# Backup thủ công
./server-scripts/auto-backup.sh
```

## 🛠️ Scripts Chính

### Scripts (Thư mục scripts/)
| Script | Mục đích | Khi nào dùng |
|--------|----------|--------------|
| `deploy-production.sh` | Deploy production đầy đủ | Lần đầu hoặc deploy major |
| `update-image.sh` | Update image với backup | Cập nhật code thường xuyên |
| `clear-cache.sh` | Xóa cache Laravel | Sau khi update config/code |
| `verify-deployment.sh` | Kiểm tra health | Sau mỗi deployment |

### Server Scripts (Thư mục server-scripts/)
| Script | Mục đích | Khi nào dùng |
|--------|----------|--------------|
| `setup-server.sh` | Cài đặt server từ đầu | Lần đầu setup server |
| `deploy.sh` | Deploy script trên server | Được gọi từ CI/CD |
| `auto-backup.sh` | Backup tự động hàng ngày | Chạy tự động hoặc thủ công |
| `monitor.sh` | Monitor hệ thống realtime | Kiểm tra trạng thái |

## 📊 Thông Tin Server

- **Server IP**: 157.173.127.217
- **Deployment Path**: `/opt/hanaya-shop`
- **Docker Registry**: `assassincreed2k1/hanaya-shop`
- **Database**: MySQL 8.0
- **Cache**: Redis 7-alpine
- **Web Server**: Nginx

## 🔧 Cấu Hình Quan Trọng

### Docker Compose
- File chính: `docker-compose.prod.yml`
- Services: app, db, redis, queue
- Volume mounts cho persistent data
- Health checks cho tất cả services

### Backup System
- **Database**: Backup hàng ngày với gzip
- **Files**: Backup storage/uploads
- **Configs**: Backup cấu hình và scripts
- **Retention**: Giữ 30 ngày backup

### Monitoring
- Container health checks
- Website accessibility
- Database connectivity
- Disk và memory usage
- Application error logs

## 🚨 Xử Lý Sự Cố

### Website Không Truy Cập Được
```bash
./server-scripts/monitor.sh          # Kiểm tra overall
./scripts/verify-deployment.sh       # Kiểm tra chi tiết
docker-compose logs app              # Xem logs
```

### Database Issues
```bash
docker-compose exec db mysql -u root -p  # Kết nối database
docker-compose logs db                    # Xem logs database
```

### High Resource Usage
```bash
docker stats                         # Xem resource usage
./server-scripts/monitor.sh          # Kiểm tra disk/memory
```

## 📋 Checklist Deployment

- [ ] ✅ Server setup hoàn thành
- [ ] ✅ Docker images đã build
- [ ] ✅ Database đã import
- [ ] ✅ Website accessible tại http://157.173.127.217
- [ ] ✅ Backup system hoạt động
- [ ] ✅ Monitoring scripts sẵn sàng
- [ ] ✅ CI/CD pipeline functional

## 📞 Hỗ Trợ

Nếu gặp vấn đề:
1. Chạy `./server-scripts/monitor.sh` để kiểm tra tổng quan
2. Xem logs: `docker-compose logs [service]`
3. Kiểm tra backup: `ls -la /opt/hanaya-shop/backups`
4. Liên hệ team development nếu cần thiết