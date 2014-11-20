[![Build Status](https://travis-ci.org/skqr/hateoas-bundle-example.svg)](https://travis-ci.org/skqr/hateoas-bundle-example)

[GOintegro](http://www.gointegro.com/en/) / HATEOAS (example)
=============================================================

An example app using the [GOintegro HATEOAS bundle](https://github.com/gointegro/hateoas-bundle).

Try it out
==========

Here's a small example app, so you can feel the HATEOAS magic in your finger tips without much ado.

If you have SQLite and a couple of minutes, you can
- clone the project,
- install the [Composer](http://getcomposer.org/) deps,
- run `app/console doctrine:schema:create` to setup the db,
- run `app/console server:run` to run the app at `127.0.0.1:8000`,

and try out the HATEOAS API on `http://127.0.0.1:8000/api/v1`.

The `/users`, `/posts`, and `/comments` resources are available.

(You'll need to create some entities using the API or by running `app/console doctrine:fixtures:load --fixtures=src/HateoasInc/Bundle/ExampleBundle/DataFixtures/ORM`.)
