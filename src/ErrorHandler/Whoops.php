<?php

namespace Rougin\Slytherin\ErrorHandler;

use Rougin\Slytherin\ErrorHandler\ErrorHandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Whoops Error Handler
 *
 * PHP errors for cool kids.
 *
 * http://filp.github.io/whoops
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Whoops implements ErrorHandlerInterface
{
    protected $environment;
    protected $whoops;

    public function __construct(Run $whoops, $environment = 'development')
    {
        $this->whoops = $whoops;
        $this->environment = $environment;
    }

    /**
     * Sets up the environment to be used.
     * 
     * @param  string $environment
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Registers the instance as an error handler.
     * 
     * @return object
     */
    public function display()
    {
        error_reporting(E_ALL);

        if ($this->environment !== 'production') {
            $this->whoops->pushHandler(new PrettyPageHandler);
        } else {
            $this->whoops->pushHandler(function ($e) {
                echo 'Friendly error page and send an email to the developer';
            });
        }

        return $this->whoops->register();
    }
}
