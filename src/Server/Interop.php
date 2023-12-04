<?php

namespace Rougin\Slytherin\Server;

use Rougin\Slytherin\Server\Handlers\Handler030;
use Rougin\Slytherin\Server\Handlers\Handler041;
use Rougin\Slytherin\Server\Handlers\Handler050;
use Rougin\Slytherin\Server\Handlers\Handler100;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 * @codeCoverageIgnore
 */
class Interop
{
    /**
     * @param  \Rougin\Slytherin\Server\HandlerInterface $handler
     * @return mixed
     */
    public static function get(HandlerInterface $handler)
    {
        switch (Version::get())
        {
            case '0.3.0':
                $handler = new Handler030($handler);

                break;
            case '0.4.1':
                $handler = new Handler041($handler);

                break;
            case '0.5.0':
                $handler = new Handler050($handler);

                break;
            case '1.0.0':
                $handler = new Handler100($handler);

                break;
        }

        return $handler;
    }
}