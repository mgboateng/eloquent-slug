<?php 
namespace MGBoateng\EloquentSlugs\Test;

use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra 
{
    public function setUp() 
    {
        parent::setUp();
        
        $this->loadMigrationsFrom(realpath(__DIR__ . '/database/migrations'));   

        $this->withFactories(__DIR__.'/database/factories');
        
    }

    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class            
        ];
    }


    public function getEnvironmentSetUp($app) 
    {
        $app['config']->set('database.default', 'testing');
        // $app['config']->set('database.connections.testing', [
        //     'driver' => 'mysql',
        //     'host' => '127.0.0.1',
        //     'database' => 'test',
        //     'username' => 'root',
        //     'password' => ''
        // ]);        
    }
}