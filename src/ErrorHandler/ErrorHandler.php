<?php

namespace Rougin\Slytherin\ErrorHandler;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Rougin\Slytherin\ErrorHandler\ErrorHandlerInterface;

/**
 * Error Handler
 *
 * A simple implementation of an error handler built on top of
 * Filipe Dobreira's Whoops - php errors for cool kids.
 *
 * http://filp.github.io/whoops
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var string
     */
    protected $environment = '';

    /**
     * @var Run
     */
    protected $whoops;

    /**
     * @param string $environment
     */
    public function __construct($environment = 'development')
    {
        $this->whoops = new Run;
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
            $this->whoops->pushHandler(function () {
                echo 'Friendly error page and send an email to the developer';
            });
        }

        return $this->whoops->register();
    }
}
