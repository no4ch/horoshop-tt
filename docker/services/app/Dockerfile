FROM alpine:3.20.0

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

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.6.2

RUN apk add --no-cache bash && \
    curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && \
    apk add symfony-cli

CMD symfony local:server:start --port=8080
