<?php

namespace Mixdinternet\Testimonials\Providers;

use Illuminate\Support\ServiceProvider;
use Mixdinternet\Testimonials\Testimonial;

use Menu;

class TestimonialsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setMenu();

        $this->setRoutes();

        $this->loadViews();

        $this->loadMigrations();

        $this->publish();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadConfigs();
    }

    protected function setMenu()
    {
        Menu::modify('adminlte-sidebar', function ($menu) {
            $menu->route('admin.testimonials.index', config('mtestimonials.name', 'Depoimentos'), [], config('mtestimonials.order', 20)
                , ['icon' => config('mtestimonials.icon', 'fa fa-file-text'), 'active' => function () {
                    return checkActive(route('admin.testimonials.index'));
                }])->hideWhen(function () {
                return checkRule('admin.testimonials.index');
            });
        });

        Menu::modify('adminlte-permissions', function ($menu) {
            $menu->url('admin.testimonials', config('mtestimonials.name', 'Depoimentos'), config('mtestimonials.order', 20));
        });
    }

    protected function setRoutes()
    {
        if (!$this->app->routesAreCached()) {
            $this->app->router->group(['namespace' => 'Mixdinternet\Testimonials\Http\Controllers'],
                function () {
                    require __DIR__ . '/../routes/web.php';
                });
        }
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mixdinternet/testimonials');
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function loadConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/maudit.php', 'maudit.alias');
        $this->mergeConfigFrom(__DIR__ . '/../config/mtestimonials.php', 'mtestimonials');
    }

    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/mixdinternet/testimonials'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('database/migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/mtestimonials.php' => base_path('config/mtestimonials.php'),
        ], 'config');
    }
}
