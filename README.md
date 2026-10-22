<img src="public/brand-mark.svg" alt="Aurora" width="80" height="80">

# Aurora

[![Latest version](https://img.shields.io/packagist/v/shipaurora/aurora)](https://packagist.org/packages/shipaurora/aurora)
[![Tests](https://github.com/shipaurora/aurora/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/shipaurora/aurora/actions/workflows/tests.yml)
[![PHP 8.4+](https://img.shields.io/badge/PHP-8.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Filament 5](https://img.shields.io/badge/Filament-5-FDAE4B)](https://filamentphp.com)
[![License: MIT](https://img.shields.io/packagist/l/shipaurora/aurora)](LICENSE)

**The starter app for a new website on [Aurora](https://shipaurora.com)**, a content management
system for Laravel, built on Filament. It's a Laravel application with the CMS already installed,
ready to make your own: editors build pages in a visual editor on the real design, and the website
stays plain Laravel code.

<img src="public/images/blocks/welcome.webp" alt="The homepage a new site starts with" width="800">

## Requirements

- PHP 8.4 or newer, with the GD or Imagick extension
- Composer 2 and Node.js 20 or newer
- A database: SQLite, MySQL/MariaDB or PostgreSQL

## Create a site

```bash
composer create-project shipaurora/aurora my-site   # asks a few questions and sets it all up
cd my-site
```

`create-project` ends by running `php artisan aurora:install`, which sets up the database and what
the CMS needs, creates your admin account, and builds the website's front end (`npm install` and
`npm run build`; Node.js is needed for that). Say yes when it offers to run the website's own
seeders: that publishes the first homepage. (Without a terminal to answer in, for example with
`--no-interaction`, it tells you to run `php artisan aurora:install` yourself.) Then open the site
(with [Laravel Herd](https://herd.laravel.com), `http://my-site.test`; otherwise `php artisan serve`)
and sign in at `/admin`.

## What's in it

| Where | What |
| --- | --- |
| `app/Blocks/WelcomeBlock.php` | The homepage's block: an example of a block of the website's own, its fields and its picture in the editor |
| `resources/views/components/blocks/welcome.blade.php` | The block's view, the same on the website and in the editor |
| `resources/views/vendor/aurora/layouts` | The page layouts (standard, landing page, minimal and blank), published to make your own |
| `resources/css/app.css` | The website's stylesheet, which the visual editor shows on its canvas too |
| `database/seeders/HomepageSeeder.php` | The first homepage, in English and Norwegian |
| `resources/lang/nb.json` | Norwegian labels for the website's own block |
| `config/aurora.php` | Aurora's settings |

## Next steps

- Edit the homepage in the visual editor, or replace it with blocks of your own.
- Add a block with `php artisan aurora:make-block "Pricing table"`.
- Make the layouts in `resources/views/vendor/aurora/layouts` look like your website.
- Add a layout of your own with `php artisan make:aurora-layout "Campaign"`.
- Add people with `php artisan make:aurora-user`, choosing each one's role.

## Documentation

Everything else (the visual editor, blocks, layouts, users and roles, languages, AI assistants,
the APIs and configuration) is in the documentation at
[shipaurora.com/docs](https://shipaurora.com/docs).

## License

Aurora is open-source software licensed under the [MIT license](LICENSE).
