# HƯỚNG DẪN XEM VÀ SỬA LOGS LỖI KHI DÙNG DOCKER TRÊN UBUNTU

## 1. Tổng quan
File này hướng dẫn cách xem và xử lý lỗi khi sử dụng Docker trên Ubuntu, bao quát các trường hợp phổ biến: lỗi container, lỗi build, lỗi mạng, lỗi volume, lỗi permission, lỗi service, lỗi ứng dụng bên trong container (PHP, Nginx, MySQL, Redis, ...).

---

## 2. Xem logs tổng thể của Docker
- Xem logs daemon Docker:
  ```bash
  sudo journalctl -u docker.service
  ```
- Xem trạng thái dịch vụ Docker:
  ```bash
  sudo systemctl status docker
  ```

---

## 3. Xem logs của container cụ thể
- Liệt kê các container:
  ```bash
  docker ps -a
  ```
- Xem logs container (theo tên hoặc ID):
  ```bash
  docker logs <container_name_or_id>
  # Xem realtime:
  docker logs -f <container_name_or_id>
  ```
- Xem logs nhiều dòng cuối:
  ```bash
  docker logs --tail 100 <container_name_or_id>
  ```

---

## 4. Xem logs ứng dụng bên trong container
- **PHP/Laravel**: Thường lưu ở `/var/www/html/storage/logs/laravel.log` hoặc `/app/storage/logs/laravel.log`.
  ```bash
  docker exec -it <container_name> tail -f /var/www/html/storage/logs/laravel.log
  ```
- **Nginx/Apache**:
  - Nginx: `/var/log/nginx/error.log`, `/var/log/nginx/access.log`
  - Apache: `/var/log/apache2/error.log`, `/var/log/apache2/access.log`
- **MySQL**: `/var/log/mysql/error.log` hoặc xem bằng lệnh:
  ```bash
  docker exec -it <container_name> cat /var/log/mysql/error.log
  ```
- **Redis**: `/data/logs/redis.log` hoặc `/var/log/redis/redis-server.log`

---

## 5. Xử lý các trường hợp lỗi phổ biến
### a. Container không start được
- Xem logs container: `docker logs <container_name>`
- Kiểm tra cấu hình Docker Compose, Dockerfile, file .env
- Kiểm tra port có bị trùng không
- Kiểm tra volume mount đúng chưa

### b. Lỗi permission (quyền truy cập file/folder)
- Thường gặp khi mount volume từ host vào container
- Sửa bằng cách cấp quyền cho thư mục:
  ```bash
  sudo chown -R 1000:1000 <folder>
  sudo chmod -R 775 <folder>
  ```
- Nếu là Laravel: cấp quyền cho `storage`, `bootstrap/cache`

### c. Lỗi database (MySQL, Redis...)
- Xem logs service tương ứng
- Kiểm tra biến môi trường DB_*
- Kiểm tra volume data có bị lỗi permission không
- Kiểm tra cấu hình mạng (network)

### d. Lỗi ứng dụng (PHP, Node, ...)
- Xem logs ứng dụng bên trong container
- Kiểm tra file cấu hình, biến môi trường
- Kiểm tra lại Dockerfile, docker-compose.yml

### e. Lỗi build image
- Xem output khi build: `docker-compose build` hoặc `docker build`
- Đọc kỹ dòng báo lỗi, thường là do thiếu file, sai lệnh, thiếu quyền

### f. Lỗi mạng (network)
- Kiểm tra network Docker: `docker network ls`, `docker network inspect <network>`
- Kiểm tra container có join đúng network không
- Kiểm tra port mapping

---

## 6. Khi nào cần xem logs ở đâu?
- **Container không chạy**: Xem logs container, logs Docker daemon
- **Web không truy cập được**: Xem logs Nginx/Apache, logs ứng dụng
- **Lỗi 500, 502, 504**: Xem logs ứng dụng, logs web server
- **Lỗi database**: Xem logs MySQL/Redis, kiểm tra volume
- **Lỗi permission**: Xem logs ứng dụng, kiểm tra quyền thư mục trên host
- **Lỗi build**: Xem output build, kiểm tra Dockerfile

---

## 7. Một số lệnh hữu ích khác
- Xem toàn bộ logs hệ thống:
  ```bash
  dmesg | tail -n 50
  sudo tail -f /var/log/syslog
  ```
- Xem logs docker-compose:
  ```bash
  docker-compose logs
  docker-compose logs -f <service>
  ```
- Xem logs nhiều container cùng lúc:
  ```bash
  docker-compose logs -f
  ```

---

## 8. Tổng kết
- Luôn đọc kỹ thông báo lỗi, xác định lỗi ở đâu (host, container, ứng dụng, service...)
- Xem đúng logs, sửa đúng chỗ
- Nếu không rõ, thử restart container, kiểm tra lại cấu hình, hỏi cộng đồng hoặc tra Google với thông báo lỗi cụ thể.

































































































































































































































































































































































































