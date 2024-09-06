FROM alpine:3.20.0

ARG APP_USER_ID=1000

WORKDIR /var/www/html/

# install base system
RUN apk update && \
    apk upgrade

RUN apk --no-cache add curl

# install php and extensions
#    php83-fpm \
RUN apk add php83 \
    php83-ctype \
    php83-iconv \
    php83-session \
    php83-tokenizer

RUN apk add php83-simplexml \
    php83-xml \
    php83-xmlwriter \
    php83-dom \
    php83-mbstring \
    php83-posix \
    php83-intl \
    php83-pdo \
    php83-opcache \
    php83-phar \
    php83-openssl \
    php83-curl

RUN apk add php83-pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.2

RUN apk add --no-cache bash && \
    curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && \
    apk add symfony-cli

RUN if [ -n "${APP_USER_ID}" ]; then \
	delgroup www-data && \
	addgroup -g ${APP_USER_ID} www-data && \
	adduser -u ${APP_USER_ID} -G www-data -D www-data && \
    chown -R www-data:www-data /var/www/html; \
fi

USER www-data

CMD symfony local:server:start --port=8080
