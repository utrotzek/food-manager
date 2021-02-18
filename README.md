Food Manager
------------
[![Generic Badge](https://img.shields.io/github/checks-status/utrotzek/food-manager/master?label=master)](https://shields.io/) [![Generic Badge](https://img.shields.io/github/checks-status/utrotzek/food-manager/develop?label=develop)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/php--coverage-100%25-success)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/js--coverage-100%25-success)](https://shields.io/)

# Development

Start the environment: `bin/container start` (use -s flag to boot only the container)

# Postman collection export

Profclems aweseom postman collection generator (big thanks) is included. A fresh export can be done using:

`bin/artisan.sh postman:collection:export MyNewExport --api`

# Techstack

* (vee validate)[https://vee-validate.logaretm.com/v4]
* (vue top progress) [https://github.com/dalphyx/vue-top-progress]
* (vuex)[https://vuex.vuejs.org/]
* (pest testing framework)[https://pestphp.com/]

# docker setup
TODO
The scheduler can be started using the same image with a different "role" using the environment variable CONTAINER_ROLE

```
  scheduler:
    environment:
      ....
      CONTAINER_ROLE: scheduler
```