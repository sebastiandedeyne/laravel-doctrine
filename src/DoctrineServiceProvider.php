<?php

namespace Sebdd\LaravelDoctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\Setup;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class DoctrineServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap doctrine services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/doctrine.php' => config_path('doctrine.php')
        ], 'config');
    }

    /**
     * Register doctrine services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->registerEntityManager();
        $this->registerCommands();
        $this->registerUserProvider();
    }

    /**
     * Merge the application configuration
     * 
     * @return void
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/doctrine.php', 'doctrine');
    }

    /**
     * Register the entity manager.
     * 
     * @return void
     */
    protected function registerEntityManager()
    {
        $config = Setup::createAnnotationMetadataConfiguration([app_path()], config('app.debug'));
        $config->setNamingStrategy(new UnderscoreNamingStrategy);
        
        $connection = [
            'driver'    => 'pdo_mysql',
            'host'      => config('database.connections.mysql.host'),
            'dbname'    => config('database.connections.mysql.database'),
            'user'      => config('database.connections.mysql.username'),
            'password'  => config('database.connections.mysql.password'),
        ];

        $entityManager = EntityManager::create($connection, $config);

        $this->app->instance(EntityManager::class, $entityManager);
    }

    /**
     * Register the doctrine commands.
     * 
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            DoctrineCommand::class
        ]);
    }

    /**
     * Register the user provider
     * 
     * @return void
     */
    protected function registerUserProvider()
    {
        if (!config('doctrine.user_provider.enabled')) {
            return;
        }

        $auth = $this->app->make('auth');

        $auth->extend('doctrine', function($app) {
            return $this->getUserProvider($app);            
        });
    }

    /**
     * Return an instance of the user provider
     * 
     * @param \Illuminate\Contracts\Foundation\Application
     * @return \Illuminate\Contracts\Auth\UserProvider
     */
    protected function getUserProvider(Application $app)
    {
        return new DoctrineUserProvider(
            $app->make(EntityManager::class),
            config('auth.model'),
            config('doctrine.user_provider.columns.identifier'),
            config('doctrine.user_provider.columns.remember_token'),
            $app->make(Hasher::class)
        );
    }
}
