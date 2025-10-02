#!/bin/bash#!/bin/bash



# Auto Backup Script for Hanaya Shop# Auto Backup Script for Hanaya Shop

# Chạy tự động hàng ngày để backup database, files và configs# Chạy tự động hàng ngày để backup database, files và configs



# Cấu hình# Cấu hình

BACKUP_DIR="/opt/hanaya-shop/backups"BACKUP_DIR="/opt/hanaya-shop/backups"

PROJECT_DIR="/opt/hanaya-shop"PROJECT_DIR="/opt/hanaya-shop"

DATE=$(date +%Y%m%d_%H%M%S)DATE=$(date +%Y%m%d_%H%M%S)

KEEP_DAYS=30KEEP_DAYS=30



# Tạo thư mục backup nếu chưa có# Tạo thư mục backup nếu chưa có

mkdir -p $BACKUP_DIR/databasemkdir -p $BACKUP_DIR/database

mkdir -p $BACKUP_DIR/filesmkdir -p $BACKUP_DIR/files

mkdir -p $BACKUP_DIR/configsmkdir -p $BACKUP_DIR/configs



echo "🔄 Bắt đầu backup tự động - $DATE"echo "🔄 Bắt đầu backup tự động - $DATE"



# 1. Backup Database# 1. Backup Database

echo "📦 Backup database..."echo "📦 Backup database..."

cd $PROJECT_DIRcd $PROJECT_DIR

docker-compose exec -T db mysqldump -u root -phanaya_db_password hanaya_shop | gzip > $BACKUP_DIR/database/hanaya_shop_$DATE.sql.gzdocker-compose exec -T db mysqldump -u root -phanaya_db_password hanaya_shop | gzip > $BACKUP_DIR/database/hanaya_shop_$DATE.sql.gz



if [ $? -eq 0 ]; thenif [ $? -eq 0 ]; then

    echo "✅ Database backup thành công"    echo "✅ Database backup thành công"

elseelse

    echo "❌ Database backup thất bại"    echo "❌ Database backup thất bại"

    exit 1    exit 1

fifi



# 2. Backup Storage Files (images, uploads)# 2. Backup Storage Files (images, uploads)

echo "📁 Backup storage files..."echo "📁 Backup storage files..."

tar -czf $BACKUP_DIR/files/storage_$DATE.tar.gz -C $PROJECT_DIR storage/app/public/tar -czf $BACKUP_DIR/files/storage_$DATE.tar.gz -C $PROJECT_DIR storage/app/public/



if [ $? -eq 0 ]; thenif [ $? -eq 0 ]; then

    echo "✅ Storage files backup thành công"    echo "✅ Storage files backup thành công"

elseelse

    echo "❌ Storage files backup thất bại"    echo "❌ Storage files backup thất bại"

fifi



# 3. Backup Configs# 3. Backup Configs

echo "⚙️ Backup configurations..."echo "⚙️ Backup configurations..."

tar -czf $BACKUP_DIR/configs/configs_$DATE.tar.gz \tar -czf $BACKUP_DIR/configs/configs_$DATE.tar.gz \

    -C $PROJECT_DIR \    -C $PROJECT_DIR \

    docker-compose.yml \    docker-compose.yml \

    .env \    .env \

    deployment/ \    deployment/ \

    --exclude=deployment/mysql/data    --exclude=deployment/mysql/data



if [ $? -eq 0 ]; thenif [ $? -eq 0 ]; then

    echo "✅ Config backup thành công"    echo "✅ Config backup thành công"

elseelse

    echo "❌ Config backup thất bại"    echo "❌ Config backup thất bại"

fifi



# 4. Dọn dẹp backup cũ (giữ lại 30 ngày)# 4. Dọn dẹp backup cũ (giữ lại 30 ngày)

echo "🧹 Dọn dẹp backup cũ..."echo "🧹 Dọn dẹp backup cũ..."



# Xóa database backup cũ# Xóa database backup cũ

find $BACKUP_DIR/database -name "*.sql.gz" -mtime +$KEEP_DAYS -deletefind $BACKUP_DIR/database -name "*.sql.gz" -mtime +$KEEP_DAYS -delete

# Xóa file backup cũDB_OLD_COUNT=$(find $BACKUP_DIR/database -name "*.sql.gz" -mtime +$KEEP_DAYS | wc -l)

find $BACKUP_DIR/files -name "*.tar.gz" -mtime +$KEEP_DAYS -delete

# Xóa config backup cũ# Xóa file backup cũ

find $BACKUP_DIR/configs -name "*.tar.gz" -mtime +$KEEP_DAYS -deletefind $BACKUP_DIR/files -name "*.tar.gz" -mtime +$KEEP_DAYS -delete

FILES_OLD_COUNT=$(find $BACKUP_DIR/files -name "*.tar.gz" -mtime +$KEEP_DAYS | wc -l)

echo "🗑️ Đã xóa backup cũ hơn $KEEP_DAYS ngày"

# Xóa config backup cũ

# 5. Thống kê backupfind $BACKUP_DIR/configs -name "*.tar.gz" -mtime +$KEEP_DAYS -delete

DB_COUNT=$(ls -1 $BACKUP_DIR/database/*.sql.gz 2>/dev/null | wc -l)CONFIG_OLD_COUNT=$(find $BACKUP_DIR/configs -name "*.tar.gz" -mtime +$KEEP_DAYS | wc -l)

FILES_COUNT=$(ls -1 $BACKUP_DIR/files/*.tar.gz 2>/dev/null | wc -l)

CONFIG_COUNT=$(ls -1 $BACKUP_DIR/configs/*.tar.gz 2>/dev/null | wc -l)echo "🗑️ Đã xóa backup cũ hơn $KEEP_DAYS ngày"



echo ""# 5. Thống kê backup

echo "📊 THỐNG KÊ BACKUP:"DB_COUNT=$(ls -1 $BACKUP_DIR/database/*.sql.gz 2>/dev/null | wc -l)

echo "   Database backups: $DB_COUNT files"FILES_COUNT=$(ls -1 $BACKUP_DIR/files/*.tar.gz 2>/dev/null | wc -l)

echo "   Storage backups:  $FILES_COUNT files"CONFIG_COUNT=$(ls -1 $BACKUP_DIR/configs/*.tar.gz 2>/dev/null | wc -l)

echo "   Config backups:   $CONFIG_COUNT files"

echo ""

# 6. Kiểm tra dung lượngecho "📊 THỐNG KÊ BACKUP:"

BACKUP_SIZE=$(du -sh $BACKUP_DIR | cut -f1)echo "   Database backups: $DB_COUNT files"

echo "   Tổng dung lượng:  $BACKUP_SIZE"echo "   Storage backups:  $FILES_COUNT files"

echo "   Config backups:   $CONFIG_COUNT files"

# 7. Kiểm tra disk space

DISK_USAGE=$(df -h $BACKUP_DIR | awk 'NR==2 {print $5}' | sed 's/%//')# 6. Kiểm tra dung lượng

if [ $DISK_USAGE -gt 85 ]; thenBACKUP_SIZE=$(du -sh $BACKUP_DIR | cut -f1)

    echo "⚠️ CẢNH BÁO: Disk usage cao ($DISK_USAGE%)"echo "   Tổng dung lượng:  $BACKUP_SIZE"

fi

# 7. Kiểm tra disk space

echo ""DISK_USAGE=$(df -h $BACKUP_DIR | awk 'NR==2 {print $5}' | sed 's/%//')

echo "✅ Backup tự động hoàn thành - $DATE"if [ $DISK_USAGE -gt 85 ]; then

echo "📍 Backup location: $BACKUP_DIR"    echo "⚠️ CẢNH BÁO: Disk usage cao ($DISK_USAGE%)"
fi

echo ""
echo "✅ Backup tự động hoàn thành - $DATE"
echo "📍 Backup location: $BACKUP_DIR"