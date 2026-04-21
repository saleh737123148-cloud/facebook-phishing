FROM php:8.1-apache

# تمكين مكتبات PHP المطلوبة
RUN docker-php-ext-install mysqli

# نسخ جميع الملفات إلى مجلد الخادم
COPY . /var/www/html/

# منح الصلاحيات
RUN chmod -R 755 /var/www/html

# فتح المنفذ 80
EXPOSE 80
