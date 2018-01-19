FROM pitchanon/docker-apache-php:5.5
#FROM ubuntu:14.04
MAINTAINER Juriy Kostianitsa <bulich@do4you.ru>

WORKDIR /var/www/code/
COPY . /var/www/code/
COPY cfg/php.ini /usr/local/etc/php/
copy cfg/vhost.conf /etc/apache2/sites-enabled/

# preparing env
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update -y
# installing apache
RUN apt-get install -y apache2
RUN mkdir -p /var/lock/apache2 /var/run/apache2

# installing php
RUN apt-get update && apt-get -y install php5-dev php-pear libcurl3-openssl-dev openssl libcurl4-openssl-dev pkg-config libssl-dev libsslcommon2-dev autoconf g++ make libssl-dev libcurl4-openssl-dev pkg-config libsasl2-dev libpcre3 libpcre3-dev
RUN pecl install mongodb
RUN echo "extension=mongodb.so" >> /etc/php5/apache2/php.ini && echo "extension=mongodb.so" >> /etc/php5/cli/php.ini

RUN a2enmod rewrite
COPY run-lamp.sh /usr/sbin/
RUN chmod +x /usr/sbin/run-lamp.sh
RUN chown -hR www-data:www-data /var/www/code/
EXPOSE 80
CMD ["/usr/sbin/run-lamp.sh"]