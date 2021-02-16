Food Manager
------------
[![Generic badge](https://img.shields.io/badge/php--coverage-25%25-orange)](https://shields.io/) [![Generic badge](https://img.shields.io/badge/js--coverage-20%25-orange)](https://shields.io/)

# Development

## Progress bar
This project uses vue ellipse progress bar. The documentation can be found here: [docs](https://github.com/setaman/vue-ellipse-progress#installation)

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