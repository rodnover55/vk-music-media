FROM php

RUN apt update && apt install -y git net-tools libzip-dev libmcrypt-dev && \
    apt-get clean all && \
    pecl install xdebug && \
    docker-php-ext-install zip mcrypt pdo iconv && \
    docker-php-ext-enable xdebug && \

    curl https://getcomposer.org/installer | php -- --quiet --install-dir=/usr/bin --filename composer

ADD bin/run.sh /bin/run.sh

RUN chmod +x /bin/run.sh

CMD /bin/run.sh