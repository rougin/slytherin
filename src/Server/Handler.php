<?php

namespace Rougin\Slytherin\Server;

use Psr\Http\Message\ServerRequestInterface;
use Rougin\Slytherin\Server\Handlers\Handler030;
use Rougin\Slytherin\Server\Handlers\Handler041;
use Rougin\Slytherin\Server\Handlers\Handler050;
use Rougin\Slytherin\Server\Handlers\Handler100;

/**
 * @package Slytherin
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Handler implements HandlerInterface
{
    protected $default;

    protected $index = 0;

    protected $stack;

    public function __construct(array $stack, $default)
    {
        $this->default = $default;

        $this->stack = $stack;
    }

    /**
     * @param  mixed $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        if (! isset($this->stack[$this->index]))
        {
            return $this->default->handle($request);
        }

        $item = $this->stack[$this->index];

        $next = $this->next();

        // @codeCoverageIgnoreStart
        if (Version::is('0.3.0'))
        {
            $next = new Handler030($next);
        }

        if (Version::is('0.4.1'))
        {
            $next = new Handler041($next);
        }

        if (Version::is('0.5.0'))
        {
            $next = new Handler050($next);
        }

        if (Version::is('1.0.0'))
        {
            $next = new Handler100($next);
        }
        // @codeCoverageIgnoreEnd

        return $item->process($request, $next);
    }

    protected function next()
    {
        $next = clone $this;

        $next->index++;

        return $next;
    }
}
