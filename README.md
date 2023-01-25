# link-shortener

Simple link shortener that allows for custom URL codes, written using
own [PHP-SDKv2](https://github.com/lopatar/PHP-SDKv2)

# Features

- Optional custom URL codes
- Realtime url-code availability checking using javascript

# Requirements

- PHP 8.2
- Composer
- MariaDB/MySQL server
- Web server (routing all requests to public/index.php)

# Installation

- Clone repository
- Run

```shell
composer install
```

- Import [DB scheme](https://github.com/lopatar/link-shortener/blob/master/db.sql)
- Edit DB credentials in App/Config.php
- Route all requests to public/index.php
- Done!

# Routing requests to index.php

- NGINX

```
    root /path/to/public;
    index index.php;

    location / {
        try_files $uri /index.php$is_args$args =404;
    }
```