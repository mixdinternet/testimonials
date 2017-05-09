# Testimonials
A simple package of testimonials

## Installation

Add to composer.json

```js
  "require": {
    "mixdinternet/testimonials": "0.1.*"
  }
```

ou

```js
  composer require mixdinternet/testimonials
```

## Service Provider

Open `config/app.php` then add

`Mixdinternet\Testimonials\Providers\TestimonialsServiceProvider::class`


## Publishing the files

```
$ php artisan vendor:publish --provider="Mixdinternet\Testimonials\Providers\TestimonialsServiceProvider" --tag="migrations"
$ php artisan vendor:publish --provider="Mixdinternet\Testimonials\Providers\TestimonialsServiceProvider" --tag="config"
```

## Running migrations

```
$ composer dump-autoload
$ php artisan migrate
```
