
# GUIDE: Viewing and Troubleshooting Docker Logs on Ubuntu

## 1. Overview
This file explains how to view and troubleshoot errors when using Docker on Ubuntu, covering common cases: container errors, build errors, network issues, volume problems, permissions, service errors, and application errors inside containers (PHP, Nginx, MySQL, Redis, ...).

---

## 2. View Docker Daemon Logs
- View Docker daemon logs:
  ```bash
  sudo journalctl -u docker.service
  ```
- Check Docker service status:
  ```bash
  sudo systemctl status docker
  ```

---

## 3. View Specific Container Logs
- List all containers:
  ```bash
  docker ps -a
  ```
- View logs for a container (by name or ID):
  ```bash
  docker logs <container_name_or_id>
  # View logs in real time:
  docker logs -f <container_name_or_id>
  ```
- View the last lines of logs:
  ```bash
  docker logs --tail 100 <container_name_or_id>
  ```

---

## 4. View Application Logs Inside Containers
- **PHP/Laravel**: Usually stored at `/var/www/html/storage/logs/laravel.log` or `/app/storage/logs/laravel.log`.
  ```bash
  docker exec -it <container_name> tail -f /var/www/html/storage/logs/laravel.log
  ```
- **Nginx/Apache**:
  - Nginx: `/var/log/nginx/error.log`, `/var/log/nginx/access.log`
  - Apache: `/var/log/apache2/error.log`, `/var/log/apache2/access.log`
- **MySQL**: `/var/log/mysql/error.log` or view with:
  ```bash
  docker exec -it <container_name> cat /var/log/mysql/error.log
  ```
- **Redis**: `/data/logs/redis.log` or `/var/log/redis/redis-server.log`

---

## 5. Troubleshooting Common Errors
### a. Container won't start
- View container logs: `docker logs <container_name>`
- Check Docker Compose, Dockerfile, and .env configuration
- Check for port conflicts
- Check if volume mounts are correct

### b. Permission errors (file/folder access)
- Common when mounting volumes from host to container
- Fix by setting permissions:
  ```bash
  sudo chown -R 1000:1000 <folder>
  sudo chmod -R 775 <folder>
  ```
- For Laravel: set permissions for `storage`, `bootstrap/cache`

### c. Database errors (MySQL, Redis...)
- View logs for the relevant service
- Check DB_* environment variables
- Check for volume permission issues
- Check network configuration

### d. Application errors (PHP, Node, ...)
- View application logs inside the container
- Check configuration files and environment variables
- Review Dockerfile and docker-compose.yml

### e. Build image errors
- View build output: `docker compose build` or `docker build`
- Read error messages carefully; usually due to missing files, wrong commands, or permission issues

### f. Network errors
- Check Docker networks: `docker network ls`, `docker network inspect <network>`
- Check if containers are joined to the correct network
- Check port mappings

---

## 6. Where to Check Logs?
- **Container won't run**: Check container logs, Docker daemon logs
- **Website not accessible**: Check Nginx/Apache logs, application logs
- **500, 502, 504 errors**: Check application logs, web server logs
- **Database errors**: Check MySQL/Redis logs, check volume
- **Permission errors**: Check application logs, check host folder permissions
- **Build errors**: Check build output, review Dockerfile

---

## 7. Other Useful Commands
- View all system logs:
  ```bash
  dmesg | tail -n 50
  sudo tail -f /var/log/syslog
  ```
- View docker-compose logs:
  ```bash
  docker compose logs
  docker compose logs -f <service>
  ```
- View logs for multiple containers at once:
  ```bash
  docker compose logs -f
  ```

---

## 8. Summary
- Always read error messages carefully and identify where the error occurs (host, container, application, service...)
- Check the correct logs and fix the right place
- If unsure, try restarting the container, review configuration, ask the community, or search Google with the specific error message.
