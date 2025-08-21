docker push assassincreed2k1/hanaya-shop:latest
docker login
docker build -t assassincreed2k1/hanaya-shop:latest .
docker push assassincreed2k1/hanaya-shop:latest

# Guide: Build and Push Docker Image to Docker Hub

## 1. Prerequisites
- Make sure Docker is installed and you have a Docker Hub account.
- Log in to Docker Hub on your local machine:

```bash
docker login
```

## 2. Build Docker Image
- From the project root directory (where the `Dockerfile` is located):

```bash
docker build -t assassincreed2k1/hanaya-shop:latest .
```

## 3. Check the built image

```bash
docker images
```

## 4. Push the image to Docker Hub

```bash
docker push assassincreed2k1/hanaya-shop:latest
```

## 5. Update the image when there are changes
- Repeat the build and push steps whenever you want to update the image:

```bash
docker build -t assassincreed2k1/hanaya-shop:latest .
docker push assassincreed2k1/hanaya-shop:latest
```

## 6. Use this image on another machine/production

Optional: Import sample data from backup (recommended for demo/testing):
```bash
# Download sample data
curl -fsSL -o hanaya-shop-backup.sql \
  https://raw.githubusercontent.com/assassincreed2k1/Hanaya-Shop/main/database/sql/hanaya-shop-backup.sql

# Import sample data
docker compose exec -T db mysql -u root -p hanaya_shop < hanaya-shop-backup.sql
```

- Just pull the image:

```bash
docker pull assassincreed2k1/hanaya-shop:latest
```

## 7. Notes
- Make sure your `Dockerfile` is optimized and does not contain sensitive information.
- You can change the tag (e.g. `:v1.0.0`) if you want to manage versions.
- If you encounter permission errors, check your Docker Hub account and image push permissions.

---

**Full example commands:**

```bash
docker login
docker build -t assassincreed2k1/hanaya-shop:latest .
docker push assassincreed2k1/hanaya-shop:latest
```

---

This file guides you to build and push your image to Docker Hub at: [https://hub.docker.com/r/assassincreed2k1/hanaya-shop](https://hub.docker.com/r/assassincreed2k1/hanaya-shop)
