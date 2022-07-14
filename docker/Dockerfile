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

RUN cd /www/php  \
    && composer install  \
    && chmod -R 777 /www/php/storage \
    && sed -i "s|include /etc/nginx/sites-enabled.*| |g" /etc/nginx/nginx.conf \
    && mkdir /ssl
    && echo '' > /ssl/pem.txt \
    && echo '' > /ssl/key.txt

RUN echo '0 */1 * * * php /www/php/artisan twLolLuckDraw' > crontab_tmp \
    && echo '30 1 * * *  php /www/php/my.nikm.cn/artisan pushBlogLink' >> crontab_tmp \
    && echo '30 1 * * 1  php /www/php/artisan hitokoto 1000' >> crontab_tmp \
    && echo '30 1 * * 2  php /www/php/artisan ssl' >> crontab_tmp \
    && crontab crontab_tmp  \
    && rm -f crontab_tmp

#启动cron定时任务,nginx和php-fpm
CMD ["sh","-c","service cron start && nginx && php-fpm"]
