<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## About Domain Oriented Laravel

First things first, I didn't come up with this re-design of the Laravel Framework.
This builds upon the very fine [blog posts](https://stitcher.io/blog/laravel-beyond-crud-01-domain-oriented-laravel)
by [Brent](https://twitter.com/brendt_gd). The steps from the first chapter of Brent's blog have already been
performed.

Keep your application layer in `app/App` directories. This is where you will find the `Console`, `Exceptions`,
`Http`, and `Providers` directories. They work the same as the default Laravel application, they're just moved
down one directory, but still in the `\App` namespace.

Place your Domain logic organized by domain concern within the `app/Domain` directory in the `\Domain` namespace.
Support logic should be in the `app/Support` directory within the `\Support` namespace.

### Caveats
Any `php artisan make` commands will place the newly created file where stock Laravel expects it to, not where
Domain Oriented Laravel expects it to be. If you need `artisan` to create the file, you will need to manually
move it to the correct directory. The default location is acceptable for database and test files.

### Additional Changes
#### Docker
This comes with an opinionated docker stack for unit testing and deployment. Prepackaged with redis, mysql:8.0,
nginx, php:7.4 and mailhog, this stack is easily customizable to suit your needs. Simply add or change a stanza
within the [docker-compose](docker-compose.yml) file. The [Makefile](Makefile) contains commands to alias common
`docker-compose exec` commands.

#### User Permissions
I have added the `spatie/data-transfer-object` and `spatie/laravel-permission` packages to the composer
environment. After migrating and seeding, you will have three sample users ; a `superAdmin`, an `employee`, and
a `subscriber`, all with password `password` and email address of `<username>@example.com`.
 
The users all have sample [roles and permissions](https://docs.spatie.be/laravel-permission/v3/introduction/)
which are defined as class constants in the [UserRoles](app/App/Admin/Permissions/UserRoles.php) and
[UserPermissions](app/App/Admin/Permissions/UserPermissions.php) classes.

Modify/remove the [migration](database/migrations/2020_05_09_000000_create_sample_user_roles.php) and
[seeder](database/seeds/UsersTableSeeder.php) files as needed. You can also add variables to your `.env` file
if you want to change the passwords, emails, or usernames.

** Note that the `laravel/ui` composer package has not been included. 

#### Composer
I added `nunomaduro/larastan`, `sensiolabs/security-checker`, and `squizlabs/php_codesniffer` to the
development environment. Unless you rely soley on your IDE to tell you that you have possibly 

The command `composer phpcs` will check your `app/` and `tests/` directories to make
sure all files are in accordance with [PSR-12](https://www.php-fig.org/psr/psr-12/).

`composer phpcbf` will correct any files that it can in those directories to make sure they follow PSR-12.

Running `composer pretest` will first check your [`composer.lock`](composer.lock) file to search for any 
[advisories](https://github.com/sensiolabs/security-checker) in the Security Advisories Database. If no
security advisories for your packages exist, it will make sure that [larastan](https://github.com/nunomaduro/larastan)
approves of your code. If all is still good, your code will be examined to make sure it is in compliance with
PSR-12 standards.

`composer test` will run your unit and feature tests.

#### Gitlab CI
Domain Oriented Laravel distribution also comes with a CI pipeline for GitLab. By default, it will install
the composer and npm dependencies and create a cache, based upon your gitlab repository and branch. It will
then, in parallel, run your unit and feature tests, and dusk tests.

If you have installed [gitlab-runner](https://docs.gitlab.com/runner/install/) locally, either through `brew`
or a GitLab source, `make ci` will run your latest git commit through the unit and feature tests. 

When you push a [tagged](https://git-scm.com/book/en/v2/Git-Basics-Tagging) commit to GitLab, the deployment
stage will run. Before you do that, create an
[Envoy.php](https://docs.gitlab.com/ee/ci/examples/laravel_with_gitlab_and_envoy/) file. Also create a
deployment user on your server with passwordless login:

```bash
ssh-keygen -o -a 100 -t ed25519
cat ~/.ssh/id_ed25519.pub >> ~/.ssh/authorized_keys
cat ~/.ssh/id_ed25519
```
* Copy your deployment user's *private key* to your clipboard. In your GitLab
project, go to **Settings** > **CI/CD** and expand **Secret variables**. Paste
the ssh_key as an Input variable value, and set the key to `SSH_PRIVATE_KEY` and
save the variables.

  This allows the CD system to open an `ssh` connection to your server.
  
* Copy your deployment user's *public key* to your clipboard. In your Gitlab
project, go to **Settings** > **Repository** and expand **Deploy Keys**. Paste
the public key in the *Key* field, and give it a name. Remember to **Add Key**.

  This allows GitLab to recognize your user and provides pull authorization from GitLab.

* You may need to give your deployment user `chmod` and `chgrp` permissions. This potentially
opens your server to an attack vector through the `Envoy.blade.php` file.

You can also add a `$LOG_SLACK_WEBHOOK_URL` variable if you want slack notifications upon deployment.

### Installation
From your command line, `composer create-project jefhar/laravel-domain-oriented` will download and install
the framework. From there, `make` will build a base docker container for your development environment. After
the container has been created, `make docker` will create all the images needed for a complete development
environment. If you do not need MailHog running just yet, `make deploy` will boot all containers except
MailHog. To install composer dependencies, `make composerinstall`, and to update composer dependencies, `make
composerupdate`. Installing npm dependencies is through `make yarninstall`. Use `make yarnupgrade` to upgrade
the npm dependencies.

Of course, creating your CSS and JavaScript resources are also `make` commands: `make npmdev`, `make npmprod`
and `make npmwatch` will create your resources. Similar `make` commands exist for `phpcs`, `phpcbf` and a
pretest.
 
From within the php-fpm container, `make refresh` will drop your database, migrate all tables, and seed
sample users.

Update your `.env` file and browse to [`http://localhost:8080`](http://localhost:8080).


```bash
composer create-project jefhar/laravel-domain-oriented blog
cd blog
make
make deploy
make composerinstall
make yarninstall
make npmdev
make pretest
make test
docker-compose exec php-fpm sh -c 'cd /application && make refresh'
```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)
- [Appoly](https://www.appoly.co.uk)
- [OP.GG](https://op.gg)
- [云软科技](http://www.yunruan.ltd/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
