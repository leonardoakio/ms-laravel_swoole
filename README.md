## Compatibilidade

| PHP   | Laravel  | Swoole | Nginx  | MySQL  |
|-------|----------|--------|--------|--------|
| 8.3.4 | 10.48.4  | 5.1.1  | 1.24.0 |  8.1.0 |

## Iniciando o projeto
Criar o arquivo `.env` no projeto
```shell
php -r "copy('.env.example', '.env');"
```    
Instale o composer para gerar as dependências do projeto
```shell
composer install
```
Gerar a API Key do projeto
```shell
php artisan key:generate
```

## Estrutura
- O projeto utiliza a lib `swooletw/laravel-swoole`
- O supervisord será responsável por executar tanto o Swoole quanto o Nginx pelo arquivo `supervisord.conf`
- O arquivo `site.conf` será o arquivo de configuração do nginx do seu sistema (faremos o proxy reverso do Nginx (`:8080`) para o Swoole (`:1215`) através do `proxy_pass`)

### Extras
Atualizar o host de bind do swoole em `config/swoole_http.php`
```php
'host' => env('SWOOLE_HTTP_HOST', '0.0.0.0')
```
Para atualizar a porta de exposição do Swoole, adicionamos a ENV que será utilizada em `config/swoole_http.php`
```php
'port' => env('SWOOLE_HTTP_PORT', '1215')
```

### Problemas
Se o supervisord não conseguir resolver o host do nginx, verificar o resolver
```shell
location @swoole {
    resolver 127.0.0.11;
    ...
}
```

## Verificar versões
#### PHP, Nginx e Swoole
```shell
php -v | php --ri swoole | nginx -v
```
#### MySQL
```shell
docker exec -it application_mysql mysql -u root -p 
```
```
SELECT VERSION();
```

## Health
Endpoint que validam a saúde da aplicação e dos serviços:

- `http://localhost:8080/api/health`
- `http://localhost:8080/api/liveness`

## Documentação 
Endpoint da aplicação: `http://localhost:8080/documentation`

A documentação da API deve ser realizada no formato YAML e são armazenados no diretório `storage/view/api-docs` pelo nome `api-docs-v1.yml`

**Referências:**
- [Especificação OpenAPI - Swagger](https://swagger.io/specification/)

## Serviços e Portas

| Container                | Host Port | Container Port (Internal) |
| ------------------------ | --------- |---------------------------|
| application_app          | `9500`    | `9500`                    |
| application_nginx        | `8080`    | `80`                      |
| application_mysql        | `3307`    | `3306`                    |


