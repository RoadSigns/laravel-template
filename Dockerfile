FROM php:8.1-fpm-alpine3.16

# run the following commands as root
USER root
RUN \
  # ensure all packages are updated
  apk update &&\
  apk upgrade

RUN \
  # install packages
  apk --no-cache add git tzdata postgresql-dev libpq build-base libzip libzip-dev libpng libpng-dev autoconf libmcrypt libmcrypt-dev icu icu-dev libxml2-dev libxslt libxslt-dev ca-certificates &&\
  # install packages from edge repository (Until they have been created in main repository)
  apk --no-cache add php81-dev --repository=http://dl-cdn.alpinelinux.org/alpine/edge/community &&\
  # install php extensions
  docker-php-ext-install pdo_pgsql gd zip opcache intl xml dom xsl soap calendar pcntl &&\
  pecl install -f mcrypt xmlrpc &&\
  docker-php-ext-enable mcrypt

RUN \
  # set timezone in London local time
  cp /usr/share/zoneinfo/Europe/London /etc/localtime &&\
  echo "Europe/London" > /etc/timezone &&\
  # install composer
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer --2

RUN \
  # remove unused packages
  apk del tzdata libpng-dev php81-dev build-base autoconf postgresql-dev icu-dev libmcrypt-dev libxml2-dev libxslt-dev libzip-dev &&\
  # remove apk cache
  rm -rf /var/cache/apk/*


# environment vars
ARG APPLICATION_ENV
ENV APPLICATION_ENV=$APPLICATION_ENV
ARG GITHUB_TOKEN
ARG GITLAB_TOKEN

RUN set -ex && apk --no-cache add openssh-client