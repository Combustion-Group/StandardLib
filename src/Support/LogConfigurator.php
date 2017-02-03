<?php

namespace Combustion\StandardLib\Support;

use Illuminate\Log\Writer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\ConfigureLogging;

/**
 * Class LogConfigurator
 *
 * @package Combustion\StandardLib\Support
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class LogConfigurator extends ConfigureLogging
{
    /**
     * @param Writer      $log
     * @param Application $app
     * @return Writer
     */
    public function configure(Writer $log, Application $app) : Writer
    {
        // If a custom Monolog configurator has been registered for the application
        // we will call that, passing Monolog along. Otherwise, we will grab the
        // the configurations for the log system and use it for configuration.
        if ($app->hasMonologConfigurator()) {
            call_user_func(
                $app->getMonologConfigurator(), $log->getMonolog()
            );
        } else {
            $this->configureHandlers($app, $log);
        }

        return $log;
    }
}
