FROM openresty/openresty:1.15.8.2-alpine-fat
ENV PHP_FPM=127.0.0.1
VOLUME ["/var/log/nginx"]


RUN opm install openresty/lua-resty-string


COPY ./ /var/www/html
COPY docker/admin-api/conf.d/ /etc/nginx/conf.d/
COPY docker/admin-api/nginx.conf /usr/local/openresty/nginx/conf/nginx.conf


CMD envsubst '$PHP_FPM' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf && /usr/local/openresty/bin/openresty -g 'daemon off;'
