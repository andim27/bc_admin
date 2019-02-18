FROM bulich/bpt-php-7

EXPOSE 80

ADD cfg/vhost.conf /etc/apache2/sites-enabled/
COPY ./cfg/php.ini /etc/php/7.0/apache2/
COPY . /var/www/code/
WORKDIR /var/www/code/
#RUN chown -hR www-data:www-data /var/www/code/
#RUN mv ./vendor/bower-asset ./vendor/bower/

CMD ["/run.sh"]

# grr, ENTRYPOINT resets CMD now
ENTRYPOINT ["/bin/bash"]
