FROM m2sh/php7

WORKDIR /var/www/html/
RUN apt-get update && apt-get install -y supervisor curl cron
RUN mkdir -p /var/log/supervisor
COPY ./code/ /var/www/html/
COPY php.ini /usr/local/etc/php/php.ini
COPY supervisord.conf /etc/supervisor/supervisord.conf
COPY ./conf.d/ /etc/supervisor/conf.d/
COPY ./cron/ /var/spool/cron/crontabs/
EXPOSE 9001
RUN chown -hR www-data:www-data /var/www/html/
RUN chmod -R 777 /var/log/supervisor/
RUN chmod -R 777 /var/www/html/storage/logs/
RUN usermod -u 1000 www-data

CMD ["/usr/bin/supervisord"]