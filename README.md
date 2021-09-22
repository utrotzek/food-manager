Food Manager
------------
[![Generic Badge](https://img.shields.io/github/checks-status/utrotzek/food-manager/master?label=master)](https://shields.io/) [![Generic Badge](https://img.shields.io/github/checks-status/utrotzek/food-manager/develop?label=develop)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/php--coverage-100%25-success)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/js--coverage-100%25-success)](https://shields.io/)

# Development

Start the environment: `bin/container start` (use -s flag to boot only the container)

## SSL certificates

To use valid ssl certificates you can use [mkcert](https://github.com/FiloSottile/mkcert)

And type the following command:

```
mkcert -key-file docker/app/certs/food-manager.key -cert-file docker/app/certs/food-manager.crt food-manager.local.de localhost 127.0.0.1
```

# Postman collection export

Profclems aweseom postman collection generator (big thanks) is included. A fresh export can be done using:

`bin/artisan.sh postman:collection:export MyNewExport --api`

# Techstack

* (vee validate)[https://vee-validate.logaretm.com/v4]
* (vue top progress) [https://github.com/dalphyx/vue-top-progress]
* (vuex)[https://vuex.vuejs.org/]
* (pest testing framework)[https://pestphp.com/]
* (Tag selector)[http://www.vue-tags-input.com/#/examples/autocomplete]
* (Advanced cropper)[https://norserium.github.io/vue-advanced-cropper/]

# docker

TODO: further documentation

An example docker-compose file could be:

```yaml
version: '3'

AppEnv: &app-env
  DB_HOST: db
  DB_PORT: 3306
  DB_DATABASE: "MY_DB"
  DB_USERNAME: "MY_USER"
  DB_PASSWORD: "MY_PASSWORD"
  APP_KEY: SOME-APPKEY
  APP_URL: http://some-url.de
  CONTAINER_ROLE: app

services:
  app:
    image: utrotzek/food-manager:latest
    ports:
      - "80:8080"
    environment:
      <<: *app-env
    restart: unless-stopped
    volumes:
      - ./storage/app:/var/www/html/app/storage/app
  scheduler:
    image: utrotzek/food-manager:latest
    environment:
      <<: *app-env
    restart: unless-stopped
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: "MY_PASSWORD"
      MYSQL_DATABASE: "MY_DB"
      MYSQL_USER: "MY_USER"
      MYSQL_PASSWORD: "MY_PASSWORD"
    restart: unless-stopped
```
