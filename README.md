# [GOintegro](http://www.gointegro.com/en/) / HATEOAS (example)

[![Build Status](https://travis-ci.org/skqr/hateoas-bundle-example.svg)](https://travis-ci.org/skqr/hateoas-bundle-example)

An example app using the [GOintegro HATEOAS bundle](https://github.com/gointegro/hateoas-bundle).

Try it out
==========

Here's a small example app, so you can feel the HATEOAS magic in your finger tips without much ado.

If you have SQLite and a couple of minutes, you can
- clone the project,
- install the [Composer](http://getcomposer.org/) deps,
- run `app/console doctrine:schema:create` to setup the db,
- run `app/console server:run` to run the app at `127.0.0.1:8000`,
- run `app/console doctrine:fixtures:load --fixtures=src/HateoasInc/Bundle/ExampleBundle/DataFixtures/ORM` to get some data,

and try out the **HATEOAS API** on `http://127.0.0.1:8000/api/v1`.

Configure [a REST client](http://www.getpostman.com/) to use [basic auth](https://en.wikipedia.org/wiki/Basic_access_authentication) with the following credentials.

- **Player 1**
  - username: `this_guy`
  - password: `cl34rt3xt`

- **Player 2**
  - username: `the_other_guy`
  - password: `b4dp4ssw0rd`

The resources `/user-groups`, `/users`, `/posts`, and `/comments` are available.

Cheers.
