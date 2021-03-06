FROM bulich/php7lx:latest
RUN apk add --no-cache \
  gcc \
  g++ \
  make \
  pcre-dev \
  openssl-dev \
  autoconf \
  php7-openssl \
  php7-dev \
  php7-pear

RUN pecl install mongodb \
    && pecl clear-cache
RUN echo "extension=mongodb.so" > /etc/php7/conf.d/mongodb.ini
ADD . /app/
ADD ./nginx-app.conf /etc/nginx/sites-enabled/site.conf
