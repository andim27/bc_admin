FROM m2sh/php7

#EXPOSE 9000
EXPOSE 81
#VOLUME /var/www/html/
WORKDIR /var/www/html/
RUN apt-get update && apt-get install -y supervisor curl cron nginx
RUN mkdir -p /var/log/supervisor
RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/supervisord.conf
COPY nginx.conf /etc/supervisor/conf.d/nginx.conf
COPY php.conf /etc/supervisor/conf.d/php.conf
COPY php.ini /usr/local/etc/php/php.ini
COPY ..//../app/api/ /var/www/html/
COPY api.conf /etc/nginx/conf.d/api.conf
EXPOSE 9001
RUN chown -hR www-data:www-data /var/www/html/
RUN chmod -R 777 /var/log/supervisor/
RUN chmod -R 777 /var/www/html/storage/logs/
RUN usermod -u 1000 www-data
#USER www-data

CMD ["/usr/bin/supervisord"]