FROM registry.cn-beijing.aliyuncs.com/maigengduo/maigengduo-base:7.3.2

WORKDIR /var/www/html
VOLUME ["/var/log/php-fpm"]

COPY docker/php-fpm/php-fpm.d/ /usr/local/etc/php-fpm.d/
COPY docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./ /var/www/html


RUN chown -R www-data:www-data . && chmod -R 775 storage && chmod -R 775 bootstrap/cache

CMD ["php-fpm"]
