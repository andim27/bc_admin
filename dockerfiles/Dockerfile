FROM bulich/bpt-php-7

EXPOSE 80

ADD cfg/vhost.conf /etc/apache2/sites-enabled/
COPY . /var/www/code/
WORKDIR /var/www/code/
RUN chown -hR www-data:www-data /var/www/code/
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer && composer config --global github-oauth.github.com 441432e93de2b333409fb15853f84adffeb148fa
RUN composer self-update && composer global require "fxp/composer-asset-plugin:~1.3" && composer install --ignore-platform-reqs
RUN mv ./vendor/bower-asset ./vendor/bower/

CMD ["/run.sh"]

# grr, ENTRYPOINT resets CMD now
ENTRYPOINT ["/bin/bash"]
