FROM ubuntu:14.04
MAINTAINER Juriy Kostianitsa <bulich@do4you.ru>

WORKDIR /var/www/code/
COPY code/ /var/www/code/
COPY config/php.ini /usr/local/etc/php/
copy config/vhost.conf /etc/apache2/sites-enabled/

# preparing env
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get update -y
# installing apache
RUN apt-get install -y apache2
RUN mkdir -p /var/lock/apache2 /var/run/apache2

# installing php
RUN apt-get install -y php5 php5-dev php5-gd php5-memcache php5-pspell php5-snmp snmp php5-xmlrpc libapache2-mod-php5 php5-cli imagemagick php5-curl
RUN a2enmod rewrite
COPY run-lamp.sh /usr/sbin/
RUN chmod +x /usr/sbin/run-lamp.sh
RUN chown -hR www-data:www-data /var/www/code/
EXPOSE 80
CMD ["/usr/sbin/run-lamp.sh"]