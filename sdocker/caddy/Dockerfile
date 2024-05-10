ARG CADDY_VERSION=2

FROM caddy:${CADDY_VERSION}-builder-alpine AS scaddy_builder

RUN xcaddy build \
    --with github.com/dunglas/mercure@main \
    --with github.com/dunglas/mercure/caddy@main
    #--with github.com/dunglas/vulcain/caddy

FROM caddy:${CADDY_VERSION} AS scaddy

WORKDIR /srv/app

ENV MERCURE_DEMO="demo /srv/mercure-assets/"
COPY --from=dunglas/mercure:v0.11 /srv/public /srv/mercure-assets/
COPY --from=scaddy_builder /usr/bin/caddy /usr/bin/caddy
COPY Caddyfile /etc/caddy/Caddyfile
