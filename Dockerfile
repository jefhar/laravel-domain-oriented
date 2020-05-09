# For unit testing and deployment
# Set the base image for subsequent instructions
FROM phpdockerio/php74-fpm:latest
ARG BUILD_DATE
ARG VCS_REF

# Update packages
RUN apt-get update \
	&& apt-get -y --no-install-recommends install \
	    php7.4-dom \
		php7.4-gd \
		php7.4-json \
		php7.4-mbstring \
        php7.4-mysql \
        php7.4-opcache \
        php7.4-pdo \
        php7.4-redis \
        php7.4-sqlite3 \
        php7.4-zip \
        sqlite \
        unzip \
    && apt-get install -y --only-upgrade php7.4-cli php7.4-common \
    && apt-get autoremove -y \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install Laravel Envoy
RUN  composer self-update \
    && composer global require "laravel/envoy=~1.0" \
    && composer clear-cache

RUN mkdir /application && ln -s /application /opt/project
