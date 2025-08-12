# Hướng dẫn Build và Đẩy Docker Image lên Docker Hub

## 1. Chuẩn bị
- Đảm bảo đã cài đặt Docker và có tài khoản Docker Hub.
- Đăng nhập Docker Hub trên máy local:

```bash
docker login
```

## 2. Build Docker Image
- Từ thư mục gốc của project (nơi có file `Dockerfile`):

```bash
docker build -t assassincreed2k1/hanaya-shop:latest .
```

## 3. Kiểm tra image vừa build

```bash
docker images
```

## 4. Đẩy image lên Docker Hub

```bash
docker push assassincreed2k1/hanaya-shop:latest
```

## 5. Cập nhật image khi có thay đổi
- Lặp lại bước build và push mỗi khi muốn cập nhật image mới:

```bash
docker build -t assassincreed2k1/hanaya-shop:latest .
docker push assassincreed2k1/hanaya-shop:latest
```

## 6. Sử dụng image này ở máy khác/production
- Chỉ cần pull về:

```bash
docker pull assassincreed2k1/hanaya-shop:latest
```

## 7. Lưu ý
- Đảm bảo file `Dockerfile` đã tối ưu và không chứa thông tin nhạy cảm.
- Có thể chỉnh sửa tag (ví dụ: `:v1.0.0`) nếu muốn quản lý version.
- Nếu gặp lỗi quyền truy cập, kiểm tra lại tài khoản Docker Hub và quyền push image.

---

**Ví dụ lệnh đầy đủ:**

```bash
docker login
docker build -t assassincreed2k1/hanaya-shop:latest .
docker push assassincreed2k1/hanaya-shop:latest
```

---

File này hướng dẫn build và đẩy image lên Docker Hub tại: [https://hub.docker.com/r/assassincreed2k1/hanaya-shop](https://hub.docker.com/r/assassincreed2k1/hanaya-shop)
