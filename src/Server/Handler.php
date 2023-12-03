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
    /**
     * @var \Rougin\Slytherin\Server\HandlerInterface
     */
    protected $default;

    /**
     * @var integer
     */
    protected $index = 0;

    /**
     * @var \Rougin\Slytherin\Server\MiddlewareInterface[]
     */
    protected $stack;

    /**
     * @param \Rougin\Slytherin\Server\MiddlewareInterface[] $stack
     * @param \Rougin\Slytherin\Server\HandlerInterface      $default
     */
    public function __construct(array $stack, HandlerInterface $default)
    {
        $this->default = $default;

        $this->stack = $stack;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request
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
        switch (Version::get())
        {
            case '0.3.0':
                $next = new Handler030($next);

                break;
            case '0.4.1':
                $next = new Handler041($next);

                break;
            case '0.5.0':
                $next = new Handler050($next);

                break;
            case '1.0.0':
                $next = new Handler100($next);

                break;
        }
        // @codeCoverageIgnoreEnd

        /** @var \Rougin\Slytherin\Server\HandlerInterface $next */
        return $item->process($request, $next);
    }

    /**
     * @return \Rougin\Slytherin\Server\HandlerInterface
     */
    protected function next()
    {
        $next = clone $this;

        $next->index++;

        return $next;
    }
}
