<?php

namespace Rougin\Slytherin\Debug;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Rougin\Slytherin\Debug\DebuggerInterface;

/**
 * Debugger
 *
 * A simple implementation of a debugger built on top of
 * Filipe Dobreira's Whoops! - php errors for cool kids.
 *
 * http://filp.github.io/whoops
 * 
 * @package Slytherin
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class WhoopsDebugger implements DebuggerInterface
{
    /**
     * @var string
     */
    protected $environment = '';

    /**
     * @var \Whoops\Run
     */
    protected $whoops;

    /**
     * @param \Whoops\Run $whoops
     * @param string      $environment
     */
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
     * Registers the instance as a debugger.
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
