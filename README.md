## Estrutura
O projeto utiliza a lib `swooletw/laravel-swoole`
O supervisord será responsável por executar tanto o Swoole quanto o Nginx
```shell
[program:nginx]
command = nginx -c /etc/nginx/nginx.conf  -g 'daemon off;'
user = root
autostart = true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

# NGINX roda na porta 80 e 443, portanto o swoole deve rodar na 8000
[program:swoole_server]
command=php /var/www/artisan swoole:http start
redirect_stderr=true
autostart=true
autorestart=true
user=root
numprocs=1
process_name=%(program_name)s_%(process_num)s
stdout_logfile=/var/www/storage/logs/swoole.log
stderr_logfile=/var/www/storage/logs/swoole-error.log```
```

O arquivo `site.conf` será o arquivo de configuração do nginx do seu sistema
Nele, faremos o proxy reverso do Nginx (:8080) para o Swoole (:1215) através do `proxy_pass`
```shell
location @swoole {
    resolver 127.0.0.11;

    set $suffix "";

    if ($uri = /index.php) {
        set $suffix ?$query_string;
    }

    proxy_http_version 1.1;
    proxy_set_header Host $http_host;
    proxy_set_header Scheme $scheme;
    proxy_set_header SERVER_PORT $server_port;
    proxy_set_header REMOTE_ADDR $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header Upgrade $http_upgrade;

    # IF https
    # proxy_set_header HTTPS "on";
    # Container name (not docker service)
    proxy_pass http://application_app:1215$suffix;
}
```

No arquivo `docker-compose.yaml`, criamos um volume no container do nginx (`applcation_nginx`) que copiará o arquivo de configuração do nginx (`site.conf`) para o container do nginx
```shell
- ./.docker/nginx/site.conf:/etc/nginx/conf.d/default.conf
```

Já no `Dockerfile`, fazemos a istalação do Swoole com o PECL
```shell
RUN pecl install swoole \
    && docker-php-ext-enable swoole
```

Também copiamos o arquivo de configuração do supervisord(`supervisord.conf`) para dentro do container e executamos o comando para garantir a inicialiação do gerenciador de processos
```shell
COPY ./.docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
...
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
```

## Dicas
Atualizar o host de bind do swoole em `config/swoole_http.php`
```php
'host' => env('SWOOLE_HTTP_HOST', '0.0.0.0')
```

## Extras
Para atualizar a porta de exposição do Swoole, adicionamos a ENV que será utilizada em `config/swoole_http.php`
```php
'port' => env('SWOOLE_HTTP_PORT', '1215')
```

## Problems
Se o supervisord não conseguir resolver o host do nginx, adicionar o resolver
```
location @swoole {
    resolver 127.0.0.11;
    
    ...
}
```
