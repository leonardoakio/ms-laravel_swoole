ARG PHP_VERSION
FROM php:${PHP_VERSION}

# Pacotes e dependências estão sendo instalados no sistema operacional base
RUN apk update && apk add --no-cache \
    autoconf \
    build-base \
    mysql-client \
    shadow \
    vim \
    openssl \
    bash \
    nginx \
    curl \
    supervisor \
    git

# Instala as extensões PHP PDO e pdo_mysql, que são necessárias para a comunicação com bancos de dados MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Instalando Redis e dependências necessárias
RUN pecl install redis  \
    && docker-php-ext-enable redis

# Instalar e Habilitar o Swoole
RUN pecl install swoole \
    && docker-php-ext-enable swoole

# Crie o diretório home do usuário www-data
RUN mkdir -p /var/www/home/www-data

# Baixa e instala o Composer, uma ferramenta para gerenciar dependências em projetos PHP.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Defina o diretório de trabalho para /var/www
WORKDIR /var/www

# Executa comandos de permissão
RUN chown -R www-data:www-data /var/www
COPY --chown=www-data:www-data ./ .

# Exclui a vendor (se já existir)
RUN rm -rf vendor
# Instala as dependências com o Composer
RUN composer install --no-interaction

# Crie o diretório de logs e defina as permissões de escrita
RUN mkdir -p /var/www/storage/logs && \
    touch /var/www/storage/logs/supervisor.log && \
    chown -R www-data:www-data /var/www/storage/logs

### Supervisor permite monitorar e controlar vários processos (LINUX)
### Bastante utilizado para manter processos em Daemon, ou seja, executando em segundo plano
COPY ./.docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

RUN apk update && \
    rm -rf /var/cache/apk/*

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
