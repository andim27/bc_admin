FROM m2sh/php7

WORKDIR /var/www/html/
RUN apt-get update && apt-get install -y supervisor curl
RUN mkdir -p /var/log/supervisor
COPY supervisord.conf /etc/supervisor/supervisord.conf
COPY laravel_worker.conf /etc/supervisor/conf.d/laravel_worker.conf
COPY php.ini /usr/local/etc/php/php.ini
COPY ./code/ /var/www/html/
EXPOSE 9001
RUN chown -hR www-data:www-data /var/www/html/
RUN chmod -R 777 /var/log/supervisor/
RUN chmod -R 777 /var/www/html/storage/logs/
RUN usermod -u 1000 www-data

CMD ["/usr/bin/supervisord"]