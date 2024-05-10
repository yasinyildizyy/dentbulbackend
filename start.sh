#!/bin/bash

. sdocker/utils.sh

# check environments.
if [ "${#1}" -ge 3 ]; then
    DOCKER_ENV=$1
else
    DOCKER_ENV="dev"
fi

# check programs.
if [ "$ENV" == "dev" ]; then
    if ! (( $(program_is_installed gem) )); then
        echo "${red}Please before install ${fawn}\"gem\"${normal}"

        exit 0
    fi
    if ! (( $(program_is_installed mkcert) )); then
        echo "${red}Please before install ${fawn}\"mkcert\"${normal}"

        exit 0
    fi
    if ! (( $(program_is_installed dnsmasq) )); then
        echo "${red}Please before install ${fawn}\"dnsmasq\"${normal}"

        exit 0
    fi
    if ! (( $(program_is_installed docker-sync) )); then
        echo "${red}Please before run command: ${fawn}\"sudo gem install docker-sync\"${normal}"

        exit 0
    fi
    if ! (( $(program_is_installed unison) )); then
        echo "${red}Please before install ${fawn}\"unison\"${normal}"

        exit 0
    fi
fi

###> Default parameters ###
NAME=$(basename "$PWD")
DOCKER_ENV_FILE='.env.docker'
DEFAULT_POSTGRES_OUT_PORT=$(getport 33060)
DEFAULT_MERCURE_PUBLISHER_JWT="myJWTKey"
DEFAULT_MERCURE_SUBSCRIBER_JWT="myJWTKey"
DEFAULT_REDIS_OUT_PORT=$(getport 6379)
DEFAULT_ELASTICSEARCH_ONE_OUT_PORT=$(getport 9200)
DEFAULT_ELASTICSEARCH_TWO_OUT_PORT=$(getport 9300)
DEFAULT_KIBANA_OUT_PORT=$(getport 5601)
DEFAULT_RABBITMQ_OUT_PORT=$(getport 5672)
DEFAULT_RABBITMQ_FRONT_OUT_PORT=$(getport 15672)
DEFAULT_MAILCATCHER_SMTP_OUT_PORT=$(getport 1025)
DEFAULT_MAILCATCHER_HTTP_OUT_PORT=$(getport 1080)
DEFAULT_DOCKER_SYNC_NAME="$NAME-unison"
HOST="$NAME.test.tr"
###< Default parameters ###

###> Symfony parameters ###
echo "Do you wanna inserting symfony parameters? (y/n)[default=n]"
read isSymfonyInsert
[[ ! $isSymfonyInsert ]] && isSymfonyInsert="n"

if echo "$isSymfonyInsert" | grep -iq "^y" ;then
    echo "use .env file for Symfony?(default=.env.local)"
    read SYMFONY_ENV_FILE
    echo "database_host(default=postgresql)"
    read DATABASE_HOST
    echo "database_name(default=app)"
    read DATABASE_NAME
    echo "database_user(default=app)"
    read DATABASE_USER
    echo "database_port(default=5432)"
    read DATABASE_PORT
    echo "database_password(default=app)"
    read DATABASE_PASSWORD
    [[ ! $SYMFONY_ENV_FILE ]] && SYMFONY_ENV_FILE=".env.local"
    [[ ! $DATABASE_HOST ]] && DATABASE_HOST="postgresql"
    [[ ! $DATABASE_NAME ]] && DATABASE_NAME="app"
    [[ ! $DATABASE_USER ]] && DATABASE_USER="app"
    [[ ! $DATABASE_PASSWORD ]] && DATABASE_PASSWORD="app"
    [[ ! $DATABASE_PORT ]] && DATABASE_PORT="5432"
    set_env "DATABASE_URL" "postgresql://$DATABASE_USER:$DATABASE_PASSWORD@$DATABASE_HOST:$DATABASE_PORT/$DATABASE_NAME" $SYMFONY_ENV_FILE
fi
###< Symfony parameters ###
echo "Do you wanna inserting docker parameters? (y/n)[default=n]"
read isDockerInsert
[[ ! $isDockerInsert ]] && isDockerInsert="n"

if echo "$isDockerInsert" | grep -iq "^y" ;then
    echo "Do you have MySQL or PostgreSQL? (y/n)[default=n]"
    read haveSql
    [[ ! $haveSql ]] && haveSql="n"

    echo "Do you have Mercure? (y/n)[default=n]"
    read haveMercure
    [[ ! $haveMercure ]] && haveMercure="n"

    echo "Do you have Elasticsearch? (y/n)[default=n]"
    read haveElasticsearch
    [[ ! $haveElasticsearch ]] && haveElasticsearch="n"

    echo "Do you have Rabbitmq? (y/n)[default=n]"
    read haveRabbitmq
    [[ ! $haveRabbitmq ]] && haveRabbitmq="n"

    echo "Do you have Redis? (y/n)[default=n]"
    read haveRedis
    [[ ! $haveRedis ]] && haveRedis="n"

    echo "Do you have Mailcatcher? (y/n)[default=n]"
    read haveMailcatcher
    [[ ! $haveMailcatcher ]] && haveMailcatcher="n"

    echo "Do you wanna auto set ports? (y/n)[default=y]"
    read isAutoSetPorts
    [[ ! $isAutoSetPorts ]] && isAutoSetPorts="y"

    if echo "$isAutoSetPorts" | grep -iq "^n" ;then
        if echo "$haveSql" | grep -iq "^y" ;then
            echo "POSTGRES_OUT_PORT(default=$DEFAULT_POSTGRES_OUT_PORT)"
            read POSTGRES_OUT_PORT
        fi
        if echo "$haveRedis" | grep -iq "^y" ;then
            echo "REDIS_OUT_PORT(default=$DEFAULT_REDIS_OUT_PORT)"
            read REDIS_OUT_PORT
        fi
        if echo "$haveElasticsearch" | grep -iq "^y" ;then
            echo "ELASTICSEARCH_ONE_OUT_PORT(default=$DEFAULT_ELASTICSEARCH_ONE_OUT_PORT)"
            read ELASTICSEARCH_ONE_OUT_PORT
            echo "ELASTICSEARCH_TWO_OUT_PORT(default=$DEFAULT_ELASTICSEARCH_TWO_OUT_PORT)"
            read ELASTICSEARCH_TWO_OUT_PORT
            echo "KIBANA_OUT_PORT(default=$DEFAULT_KIBANA_OUT_PORT)"
            read KIBANA_OUT_PORT
        fi
        if echo "$haveRabbitmq" | grep -iq "^y" ;then
            echo "RABBITMQ_OUT_PORT(default=$DEFAULT_RABBITMQ_OUT_PORT)"
            read RABBITMQ_OUT_PORT
            echo "RABBITMQ_FRONT_OUT_PORT(default=$DEFAULT_RABBITMQ_FRONT_OUT_PORT)"
            read RABBITMQ_FRONT_OUT_PORT
        fi
        if echo "$haveMailcatcher" | grep -iq "^y" ;then
            echo "MAILCATCHER_SMTP_OUT_PORT(default=$DEFAULT_MAILCATCHER_SMTP_OUT_PORT)"
            read MAILCATCHER_SMTP_OUT_PORT
            echo "MAILCATCHER_HTTP_OUT_PORT(default=$DEFAULT_MAILCATCHER_HTTP_OUT_PORT)"
            read MAILCATCHER_HTTP_OUT_PORT
        fi
    else
        if echo "$haveSql" | grep -iq "^y" ;then
            echo "POSTGRES_OUT_PORT=$DEFAULT_POSTGRES_OUT_PORT"
        fi
        if echo "$haveRedis" | grep -iq "^y" ;then
            echo "REDIS_OUT_PORT=$DEFAULT_REDIS_OUT_PORT"
        fi
        if echo "$haveElasticsearch" | grep -iq "^y" ;then
            echo "ELASTICSEARCH_ONE_OUT_PORT=$DEFAULT_ELASTICSEARCH_ONE_OUT_PORT"
            echo "ELASTICSEARCH_TWO_OUT_PORT=$DEFAULT_ELASTICSEARCH_TWO_OUT_PORT"
            echo "KIBANA_OUT_PORT=$DEFAULT_KIBANA_OUT_PORT"
        fi
        if echo "$haveRabbitmq" | grep -iq "^y" ;then
            echo "RABBITMQ_OUT_PORT=$DEFAULT_RABBITMQ_OUT_PORT"
            echo "RABBITMQ_FRONT_OUT_PORT=$DEFAULT_RABBITMQ_FRONT_OUT_PORT"
        fi
        if echo "$haveMailcatcher" | grep -iq "^y" ;then
            echo "MAILCATCHER_SMTP_OUT_PORT=$DEFAULT_MAILCATCHER_SMTP_OUT_PORT"
            echo "MAILCATCHER_HTTP_OUT_PORT=$DEFAULT_MAILCATCHER_HTTP_OUT_PORT"
        fi
    fi
    if echo "$haveSql" | grep -iq "^y" ;then
        echo "POSTGRES_ROOT_PASSWORD(default=root)"
        read POSTGRES_ROOT_PASSWORD
        echo "POSTGRES_DATABASE(default=app)"
        read POSTGRES_DATABASE
        echo "POSTGRES_USER(default=app)"
        read POSTGRES_USER
        echo "POSTGRES_PASSWORD(default=app)"
        read POSTGRES_PASSWORD
        [[ !$POSTGRES_ROOT_PASSWORD ]] && POSTGRES_ROOT_PASSWORD="root"
        [[ !$POSTGRES_DATABASE ]] && POSTGRES_DATABASE="app"
        [[ !$POSTGRES_USER ]] && POSTGRES_USER="app"
        [[ !$POSTGRES_PASSWORD ]] && POSTGRES_PASSWORD="app"
        [[ !$POSTGRES_OUT_PORT ]] && POSTGRES_OUT_PORT="$DEFAULT_POSTGRES_OUT_PORT"
    fi
    if echo "$haveMercure" | grep -iq "^y" ;then
        echo "MERCURE_PUBLISHER_JWT(default=$DEFAULT_MERCURE_PUBLISHER_JWT)"
        read MERCURE_PUBLISHER_JWT
        echo "MERCURE_SUBSCRIBER_JWT(default=$DEFAULT_MERCURE_SUBSCRIBER_JWT)"
        read MERCURE_SUBSCRIBER_JWT
        [[ !$MERCURE_PUBLISHER_JWT ]] && MERCURE_PUBLISHER_JWT="$DEFAULT_MERCURE_PUBLISHER_JWT"
        [[ !$MERCURE_SUBSCRIBER_JWT ]] && MERCURE_SUBSCRIBER_JWT="$DEFAULT_MERCURE_SUBSCRIBER_JWT"
    fi
    if echo "$haveRedis" | grep -iq "^y" ;then
        [[ !$REDIS_OUT_PORT ]] && REDIS_OUT_PORT="$DEFAULT_REDIS_OUT_PORT"
    fi
    if echo "$haveElasticsearch" | grep -iq "^y" ;then
        [[ !$ELASTICSEARCH_ONE_OUT_PORT ]] && ELASTICSEARCH_ONE_OUT_PORT="$DEFAULT_ELASTICSEARCH_ONE_OUT_PORT"
        [[ !$ELASTICSEARCH_TWO_OUT_PORT ]] && ELASTICSEARCH_TWO_OUT_PORT="$DEFAULT_ELASTICSEARCH_TWO_OUT_PORT"
        [[ !$KIBANA_OUT_PORT ]] && KIBANA_OUT_PORT="$DEFAULT_KIBANA_OUT_PORT"
    fi
    if echo "$haveRabbitmq" | grep -iq "^y" ;then
        [[ !$RABBITMQ_OUT_PORT ]] && RABBITMQ_OUT_PORT="$DEFAULT_RABBITMQ_OUT_PORT"
        [[ !$RABBITMQ_FRONT_OUT_PORT ]] && RABBITMQ_FRONT_OUT_PORT="$DEFAULT_RABBITMQ_FRONT_OUT_PORT"
    fi
    if echo "$haveMailcatcher" | grep -iq "^y" ;then
        [[ !$MAILCATCHER_SMTP_OUT_PORT ]] && MAILCATCHER_SMTP_OUT_PORT="$DEFAULT_MAILCATCHER_SMTP_OUT_PORT"
        [[ !$MAILCATCHER_HTTP_OUT_PORT ]] && MAILCATCHER_HTTP_OUT_PORT="$DEFAULT_MAILCATCHER_HTTP_OUT_PORT"
    fi
    set_env "DOCKER_ENV" $DOCKER_ENV $DOCKER_ENV_FILE
    set_env "NAME" $NAME $DOCKER_ENV_FILE
    set_env "HOST" $HOST $DOCKER_ENV_FILE
    if echo "$haveSql" | grep -iq "^y" ;then
        set_env "POSTGRES_OUT_PORT" $POSTGRES_OUT_PORT $DOCKER_ENV_FILE
        set_env "POSTGRES_ROOT_PASSWORD" $POSTGRES_ROOT_PASSWORD $DOCKER_ENV_FILE
        set_env "POSTGRES_DATABASE" $POSTGRES_DATABASE $DOCKER_ENV_FILE
        set_env "POSTGRES_USER" $POSTGRES_USER $DOCKER_ENV_FILE
        set_env "POSTGRES_PASSWORD" $POSTGRES_PASSWORD $DOCKER_ENV_FILE
    fi
    if echo "$haveMercure" | grep -iq "^y" ;then
        set_env "MERCURE_PUBLISHER_JWT" $MERCURE_PUBLISHER_JWT $DOCKER_ENV_FILE
        set_env "MERCURE_SUBSCRIBER_JWT" $MERCURE_SUBSCRIBER_JWT $DOCKER_ENV_FILE
    fi
    if echo "$haveRedis" | grep -iq "^y" ;then
        set_env "REDIS_OUT_PORT" $REDIS_OUT_PORT $DOCKER_ENV_FILE
    fi
    if echo "$haveElasticsearch" | grep -iq "^y" ;then
        set_env "ELASTICSEARCH_ONE_OUT_PORT" $ELASTICSEARCH_ONE_OUT_PORT $DOCKER_ENV_FILE
        set_env "ELASTICSEARCH_TWO_OUT_PORT" $ELASTICSEARCH_TWO_OUT_PORT $DOCKER_ENV_FILE
        set_env "KIBANA_OUT_PORT" $KIBANA_OUT_PORT $DOCKER_ENV_FILE
    fi
    if echo "$haveRabbitmq" | grep -iq "^y" ;then
        set_env "RABBITMQ_OUT_PORT" $RABBITMQ_OUT_PORT $DOCKER_ENV_FILE
        set_env "RABBITMQ_FRONT_OUT_PORT" $RABBITMQ_FRONT_OUT_PORT $DOCKER_ENV_FILE
    fi
    if echo "$haveMailcatcher" | grep -iq "^y" ;then
        set_env "MAILCATCHER_SMTP_OUT_PORT" $MAILCATCHER_SMTP_OUT_PORT $DOCKER_ENV_FILE
        set_env "MAILCATCHER_HTTP_OUT_PORT" $MAILCATCHER_HTTP_OUT_PORT $DOCKER_ENV_FILE
    fi
fi

set -a
source ${DOCKER_ENV_FILE}

if [ "$DOCKER_ENV" == "dev" ]; then
    if [ "$DOCKER_SYNC_NAME" == "" ]; then
        echo "DOCKER_SYNC_NAME[default=$DEFAULT_DOCKER_SYNC_NAME]"
        read DOCKER_SYNC_NAME
        [[ ! $DOCKER_SYNC_NAME ]] && DOCKER_SYNC_NAME="$DEFAULT_DOCKER_SYNC_NAME"
        echo "DOCKER_SYNC_NAME=$DOCKER_SYNC_NAME" >> ${DOCKER_ENV_FILE}
    fi

    echo "Do you want to creating unison image? (y/n)[default=n]"
    read wantUnison
    [[ ! $wantUnison ]] && wantUnison="n"
    if echo "$wantUnison" | grep -iq "^y" ;then
        echo "Git clone repository = n, Dockerfile = y? (y/n)[default=y]"
        read isUnisonDockerfile
        [[ ! $isUnisonDockerfile ]] && isUnisonDockerfile="y"
        if echo "$isUnisonDockerfile" | grep -iq "^y" ;then
            echo "Docker sync unison building..."
            docker build -t ${DOCKER_SYNC_NAME} ./sdocker/unison/.
            echo "Docker sync unison created."
        else
            echo "Docker sync unison creating..."
            git clone https://github.com/safaksaylam/docker-image-unison.git ./sdocker/unison/installer
            echo "Docker sync unison building..."
            cd ./sdocker/unison/installer
            docker build --build-arg "OCAML_VERSION=4.06.1" --build-arg "UNISON_VERSION=2.51.2" -t ${DOCKER_SYNC_NAME} .
            cd ../../.. && rm -rf sdocker/unison/installer
            echo "Docker sync unison created."
        fi
    fi
fi

echo "Preparing app..."
if [ "$DOCKER_ENV" == "dev" ]; then
    docker_sync_volume_create
    docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d --remove-orphans
    docker-sync start
else
    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --remove-orphans
fi
echo "Project ready..."
