ARG PHP_VERSION=8.1-fpm-alpine

FROM php:${PHP_VERSION}

# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
		jq \
		bash \
		git \
		openssh \
		# for rabbitmq.
		rabbitmq-c-dev \
		# for postgresql.
		postgresql-dev \
		postgresql-client \
        # front. \
        nodejs \
        yarn \
	;

ARG APCU_VERSION=5.1.19
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		zip \
		# for pdo.
		pdo \
		# for postgresql.
		pdo_pgsql \
		pgsql \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
		# for redis.
		redis \
		# for rabbitmq.
		amqp-1.11.0beta \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
		# for redis.
		redis \
		# for rabbitmq.
		amqp \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG DOCKER_ENV="prod"
RUN if [ "$DOCKER_ENV" == "dev" ]; then ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini; else ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini; fi
COPY conf.d/symfony.$DOCKER_ENV.ini $PHP_INI_DIR/conf.d/symfony.ini

RUN set -eux; \
	{ \
		echo '[www]'; \
		echo 'ping.path = /ping'; \
	} | tee /usr/local/etc/php-fpm.d/docker-healthcheck.conf

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/app

# Allow to use development versions of Symfony
ARG STABILITY="stable"
ENV STABILITY ${STABILITY:-stable}

# Allow to select skeleton version
ARG SYMFONY_VERSION=""

# Download the Symfony skeleton and leverage Docker cache layers
RUN composer create-project "symfony/skeleton ${SYMFONY_VERSION}" . --stability=$STABILITY --prefer-dist --no-dev --no-progress --no-interaction; \
	composer clear-cache

###> recipes ###
###< recipes ###

COPY . .

RUN set -eux; \
	mkdir -p var/cache var/log; \
	chmod +x bin/console; sync
VOLUME /srv/app/var

COPY docker-healthcheck.sh /usr/local/bin/docker-healthcheck
RUN chmod +x /usr/local/bin/docker-healthcheck

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
