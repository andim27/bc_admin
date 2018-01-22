FROM pitchanon/docker-apache-php:php-7.0
# Install base packages
RUN export DEBIAN_FRONTEND="noninteractive" && \
    apt-get update && \
    apt-get -yq --force-yes upgrade && \
    apt-get -yq --force-yes install --no-install-recommends \
        apache2 \
        libapache2-mod-php7.0 \
        php7.0-dev \
	pkg-config \
	build-essential \
	libssl-dev \
	libsslcommon2-dev \
        php-pear && \
    apt-get -y clean && \
    rm -rf /var/lib/apt/lists/*

RUN sed -i "s/variables_order.*/variables_order = \"EGPCS\"/g" /etc/php/7.0/apache2/php.ini

# Enable apache modules
RUN a2dismod mpm_event && \
    a2enmod mpm_prefork rewrite vhost_alias headers ssl php7.0

RUN rm -f /etc/apache2/sites-enabled/000-default.conf

ADD run.sh /run.sh

VOLUME ["/var/log/apache2"]

EXPOSE 80 443

RUN pecl install mongodb
RUN echo "extension=mongodb.so" >> /etc/php/7.0/apache2/php.ini && echo "extension=mongodb.so" >> /etc/php/7.0/cli/php.ini

ADD cfg/vhost.conf /etc/apache2/sites-enabled/
COPY . /var/www/code/
RUN chown -hR www-data:www-data /var/www/code/

CMD ["/run.sh"]

# grr, ENTRYPOINT resets CMD now
ENTRYPOINT ["/bin/bash"]
