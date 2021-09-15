<?php

namespace App\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\LineFormatter;

class LoggingServiceProvider extends ServiceProvider {

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot() {

        // Создание канала для почты
        $this->app->make('config')->set("logging.channels.mail", [
            'driver'                    => 'daily',
            'path'                      => storage_path('logs/mail/mail.log'),
            'formatter'                 => LineFormatter::class,
            'formatter_with'            => [
                'format'                => "[%datetime%] mail.%level_name%: %message%" . PHP_EOL,
                'dateFormat'            => 'Y-m-d H:i:s',
                'allowInlineLineBreaks' => true
            ],
            'level'                     => env('LOG_LEVEL', 'debug'),
            'days'                      => env('LOG_DAYS', 30),
        ]);
    }
}
