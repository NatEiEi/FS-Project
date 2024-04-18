ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-apache
RUN groupadd -r apache && useradd -r -g apache apache-user
# ติดตั้งเครื่องมือสำหรับการติดตั้งเพิ่มเติม
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# ติดตั้ง extension ที่จำเป็น
RUN docker-php-ext-install pdo_mysql mysqli

# เปิดใช้งาน extension
RUN docker-php-ext-enable pdo_mysql mysqli

USER apache-user
COPY . /var/www/html/