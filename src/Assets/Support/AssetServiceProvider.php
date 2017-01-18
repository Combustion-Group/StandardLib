<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 10:08 AM
 */

namespace Combustion\StandardLib\Assets\Support;

use Combustion\StandardLib\Assets\AssetsGateway;
use Combustion\StandardLib\Assets\FileGateway;
use Combustion\StandardLib\Assets\ImageGateway;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
class AssetServiceProvider extends ServiceProvider
{
    /**
     * Create the User Gateway as a singleton
     */
    public function register()
    {
        $this->app->singleton(AssetsGateway::class, function (Application $app, array $params = []) {
            $config = $app['config']['core.packages'][AssetsGateway::class];
            // build drivers array
            $drivers = array();
            foreach ($config['drivers'] as $driverName => $driverInfo) {
                $drivers[$driverName] = $app->make($driverInfo['class']);
            }
            return new AssetsGateway(
                $config,
                $drivers
            );
        });
        $this->app->singleton(ImageGateway::class, function (Application $app, array $params = []) {
            $config = $app['config']['core.packages'][ImageGateway::class]['drivers'][ImageGateway::DOCUMENT_TYPE]['config'];
            return new ImageGateway(
                $config,
                $app->make(FileGateway::class)
            );
        });
        $this->app->singleton(FileGateway::class, function (Application $app, array $params = []) {
            $config = $app['config']['core.packages'][FileGateway::class];
            return new FileGateway(
                $config
            );
        });
    }
}