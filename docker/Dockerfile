FROM docker.nikm.cn/wuruiwm/php:7.2

RUN apt-get update \
    && apt-get install -y \
    #不安装不必须的依赖
    --no-install-recommends \
    #nginx
    nginx \
    #清理apt缓存
    && rm -r /var/lib/apt/lists/*  \
    && nginx

COPY docker/nginx /etc/nginx/conf.d

COPY . /www/php

RUN cp /www/php/.env.example /www/php/.env
RUN cd /www/php && composer install

RUN sed -i "s|include /etc/nginx/sites-enabled.*| |g" /etc/nginx/nginx.conf

#启动cron定时任务,nginx和php-fpm
CMD ["sh","-c","service cron start && nginx && php-fpm"]
