#!/bin/bash#!/bin/bash



# Monitor Script for Hanaya Shop# Monitor Script for Hanaya Shop

# Kiểm tra trạng thái hệ thống và gửi cảnh báo# Kiểm tra trạng thái hệ thống và gửi cảnh báo



PROJECT_DIR="/opt/hanaya-shop"PROJECT_DIR="/opt/hanaya-shop"

LOG_FILE="/var/log/hanaya-monitor.log"LOG_FILE="/var/log/hanaya-monitor.log"



# Colors# Colors

RED='\033[0;31m'RED='\033[0;31m'

GREEN='\033[0;32m'GREEN='\033[0;32m'

YELLOW='\033[0;33m'YELLOW='\033[0;33m'

NC='\033[0m' # No ColorNC='\033[0m' # No Color



log_message() {log_message() {

    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a $LOG_FILE    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a $LOG_FILE

}}



check_containers() {check_containers() {

    echo -e "${YELLOW}🔍 Kiểm tra Docker containers...${NC}"    echo -e "${YELLOW}🔍 Kiểm tra Docker containers...${NC}"

        

    cd $PROJECT_DIR    cd $PROJECT_DIR

        

    # Kiểm tra tất cả containers    # Kiểm tra tất cả containers

    CONTAINERS=("hanaya-shop-app-1" "hanaya-shop-db-1" "hanaya-shop-redis-1" "hanaya-shop-queue-1")    CONTAINERS=("hanaya-shop-app-1" "hanaya-shop-db-1" "hanaya-shop-redis-1" "hanaya-shop-queue-1")

        

    for container in "${CONTAINERS[@]}"; do    for container in "${CONTAINERS[@]}"; do

        if docker ps --format "table {{.Names}}" | grep -q "$container"; then        if docker ps --format "table {{.Names}}" | grep -q "$container"; then

            STATUS=$(docker inspect --format='{{.State.Status}}' $container)            STATUS=$(docker inspect --format='{{.State.Status}}' $container)

            if [ "$STATUS" = "running" ]; then            if [ "$STATUS" = "running" ]; then

                echo -e "  ✅ $container: ${GREEN}Running${NC}"                echo -e "  ✅ $container: ${GREEN}Running${NC}"

                log_message "✅ $container is running"                log_message "✅ $container is running"

            else            else

                echo -e "  ❌ $container: ${RED}$STATUS${NC}"                echo -e "  ❌ $container: ${RED}$STATUS${NC}"

                log_message "❌ $container is $STATUS"                log_message "❌ $container is $STATUS"

            fi            fi

        else        else

            echo -e "  ❌ $container: ${RED}Not found${NC}"            echo -e "  ❌ $container: ${RED}Not found${NC}"

            log_message "❌ $container not found"            log_message "❌ $container not found"

        fi        fi

    done    done

}}



check_website() {check_website() {

    echo -e "${YELLOW}🌐 Kiểm tra website...${NC}"    echo -e "${YELLOW}🌐 Kiểm tra website...${NC}"

        

    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost)    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost)

        

    if [ "$HTTP_CODE" = "200" ]; then    if [ "$HTTP_CODE" = "200" ]; then

        echo -e "  ✅ Website: ${GREEN}OK (HTTP $HTTP_CODE)${NC}"        echo -e "  ✅ Website: ${GREEN}OK (HTTP $HTTP_CODE)${NC}"

        log_message "✅ Website is accessible (HTTP $HTTP_CODE)"        log_message "✅ Website is accessible (HTTP $HTTP_CODE)"

    else    else

        echo -e "  ❌ Website: ${RED}Error (HTTP $HTTP_CODE)${NC}"        echo -e "  ❌ Website: ${RED}Error (HTTP $HTTP_CODE)${NC}"

        log_message "❌ Website error (HTTP $HTTP_CODE)"        log_message "❌ Website error (HTTP $HTTP_CODE)"

    fi    fi

}}



check_database() {check_database() {

    echo -e "${YELLOW}🗄️ Kiểm tra database...${NC}"    echo -e "${YELLOW}🗄️ Kiểm tra database...${NC}"

        

    cd $PROJECT_DIR    cd $PROJECT_DIR

    DB_STATUS=$(docker-compose exec -T db mysql -u root -phanaya_db_password -e "SELECT 1" 2>/dev/null)    DB_STATUS=$(docker-compose exec -T db mysql -u root -phanaya_db_password -e "SELECT 1" 2>/dev/null)

        

    if [ $? -eq 0 ]; then    if [ $? -eq 0 ]; then

        echo -e "  ✅ Database: ${GREEN}Connected${NC}"        echo -e "  ✅ Database: ${GREEN}Connected${NC}"

        log_message "✅ Database connection OK"        log_message "✅ Database connection OK"

    else    else

        echo -e "  ❌ Database: ${RED}Connection failed${NC}"        echo -e "  ❌ Database: ${RED}Connection failed${NC}"

        log_message "❌ Database connection failed"        log_message "❌ Database connection failed"

    fi    fi

}}



check_redis() {check_redis() {

    echo -e "${YELLOW}🔴 Kiểm tra Redis...${NC}"    echo -e "${YELLOW}🔴 Kiểm tra Redis...${NC}"

        

    cd $PROJECT_DIR    cd $PROJECT_DIR

    REDIS_STATUS=$(docker-compose exec -T redis redis-cli ping 2>/dev/null)    REDIS_STATUS=$(docker-compose exec -T redis redis-cli ping 2>/dev/null)

        

    if [ "$REDIS_STATUS" = "PONG" ]; then    if [ "$REDIS_STATUS" = "PONG" ]; then

        echo -e "  ✅ Redis: ${GREEN}Connected${NC}"        echo -e "  ✅ Redis: ${GREEN}Connected${NC}"

        log_message "✅ Redis connection OK"        log_message "✅ Redis connection OK"

    else    else

        echo -e "  ❌ Redis: ${RED}Connection failed${NC}"        echo -e "  ❌ Redis: ${RED}Connection failed${NC}"

        log_message "❌ Redis connection failed"        log_message "❌ Redis connection failed"

    fi    fi

}}



check_disk_space() {check_disk_space() {

    echo -e "${YELLOW}💾 Kiểm tra disk space...${NC}"    echo -e "${YELLOW}💾 Kiểm tra disk space...${NC}"

        

    DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')    DISK_USAGE=$(df -h / | awk 'NR==2 {print $5}' | sed 's/%//')

        

    if [ $DISK_USAGE -lt 80 ]; then    if [ $DISK_USAGE -lt 80 ]; then

        echo -e "  ✅ Disk usage: ${GREEN}$DISK_USAGE%${NC}"        echo -e "  ✅ Disk usage: ${GREEN}$DISK_USAGE%${NC}"

        log_message "✅ Disk usage OK ($DISK_USAGE%)"        log_message "✅ Disk usage OK ($DISK_USAGE%)"

    elif [ $DISK_USAGE -lt 90 ]; then    elif [ $DISK_USAGE -lt 90 ]; then

        echo -e "  ⚠️ Disk usage: ${YELLOW}$DISK_USAGE%${NC}"        echo -e "  ⚠️ Disk usage: ${YELLOW}$DISK_USAGE%${NC}"

        log_message "⚠️ Disk usage warning ($DISK_USAGE%)"        log_message "⚠️ Disk usage warning ($DISK_USAGE%)"

    else    else

        echo -e "  ❌ Disk usage: ${RED}$DISK_USAGE%${NC}"        echo -e "  ❌ Disk usage: ${RED}$DISK_USAGE%${NC}"

        log_message "❌ Disk usage critical ($DISK_USAGE%)"        log_message "❌ Disk usage critical ($DISK_USAGE%)"

    fi    fi

}}



check_memory() {check_memory() {

    echo -e "${YELLOW}🧠 Kiểm tra memory...${NC}"    echo -e "${YELLOW}🧠 Kiểm tra memory...${NC}"

        

    MEMORY_USAGE=$(free | grep Mem | awk '{printf "%.0f", $3/$2 * 100.0}')    MEMORY_USAGE=$(free | grep Mem | awk '{printf "%.0f", $3/$2 * 100.0}')

        

    if [ $MEMORY_USAGE -lt 80 ]; then    if [ $MEMORY_USAGE -lt 80 ]; then

        echo -e "  ✅ Memory usage: ${GREEN}$MEMORY_USAGE%${NC}"        echo -e "  ✅ Memory usage: ${GREEN}$MEMORY_USAGE%${NC}"

        log_message "✅ Memory usage OK ($MEMORY_USAGE%)"        log_message "✅ Memory usage OK ($MEMORY_USAGE%)"

    elif [ $MEMORY_USAGE -lt 90 ]; then    elif [ $MEMORY_USAGE -lt 90 ]; then

        echo -e "  ⚠️ Memory usage: ${YELLOW}$MEMORY_USAGE%${NC}"        echo -e "  ⚠️ Memory usage: ${YELLOW}$MEMORY_USAGE%${NC}"

        log_message "⚠️ Memory usage warning ($MEMORY_USAGE%)"        log_message "⚠️ Memory usage warning ($MEMORY_USAGE%)"

    else    else

        echo -e "  ❌ Memory usage: ${RED}$MEMORY_USAGE%${NC}"        echo -e "  ❌ Memory usage: ${RED}$MEMORY_USAGE%${NC}"

        log_message "❌ Memory usage critical ($MEMORY_USAGE%)"        log_message "❌ Memory usage critical ($MEMORY_USAGE%)"

    fi    fi

}}



check_logs() {check_logs() {

    echo -e "${YELLOW}📋 Kiểm tra logs gần đây...${NC}"    echo -e "${YELLOW}📋 Kiểm tra logs gần đây...${NC}"

        

    cd $PROJECT_DIR    cd $PROJECT_DIR

        

    # Kiểm tra error logs trong 5 phút gần đây    # Kiểm tra error logs trong 5 phút gần đây

    ERROR_COUNT=$(docker-compose logs --since=5m app 2>/dev/null | grep -i error | wc -l)    ERROR_COUNT=$(docker-compose logs --since=5m app 2>/dev/null | grep -i error | wc -l)

        

    if [ $ERROR_COUNT -eq 0 ]; then    if [ $ERROR_COUNT -eq 0 ]; then

        echo -e "  ✅ Application logs: ${GREEN}No errors${NC}"        echo -e "  ✅ Application logs: ${GREEN}No errors${NC}"

        log_message "✅ No application errors in last 5 minutes"        log_message "✅ No application errors in last 5 minutes"

    else    else

        echo -e "  ⚠️ Application logs: ${YELLOW}$ERROR_COUNT errors${NC}"        echo -e "  ⚠️ Application logs: ${YELLOW}$ERROR_COUNT errors${NC}"

        log_message "⚠️ $ERROR_COUNT application errors in last 5 minutes"        log_message "⚠️ $ERROR_COUNT application errors in last 5 minutes"

    fi    fi

}}



# Main monitoring function# Main monitoring function

main() {main() {

    echo -e "${GREEN}🚀 === HANAYA SHOP SYSTEM MONITOR ===${NC}"    echo -e "${GREEN}🚀 === HANAYA SHOP SYSTEM MONITOR ===${NC}"

    echo "Time: $(date)"    echo "Time: $(date)"

    echo ""    echo ""

        

    check_containers    check_containers

    echo ""    echo ""

        

    check_website    check_website

    echo ""    echo ""

        

    check_database    check_database

    echo ""    echo ""

        

    check_redis    check_redis

    echo ""    echo ""

        

    check_disk_space    check_disk_space

    echo ""    echo ""

        

    check_memory    check_memory

    echo ""    echo ""

        

    check_logs    check_logs

    echo ""    echo ""

        

    echo -e "${GREEN}✅ Monitor check completed${NC}"    echo -e "${GREEN}✅ Monitor check completed${NC}"

}}



# Chạy monitoring# Chạy monitoring

mainmain